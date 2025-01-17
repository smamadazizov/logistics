<?php


namespace App\Http\Controllers\Trips;


use App\Data\Helpers\GenerateTripStoredItemListHelper;
use App\Data\RequestWriters\Trips\ChangeItemsTripRequest;
use App\Data\RequestWriters\Trips\UnloadItemsFromCarRequestWriter;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Branches\Storage;
use App\Models\StoredItems\StoredItem;
use App\Models\Trip;
use App\Services\Storage\ItemsStorageHistoryService;
use App\Services\StoredItem\Trip\StoredItemTripHistoryService;
use App\Services\Trip\AssociateItemsToTripsService;
use App\Services\Trip\LoadTripItemsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TripStoredItemsController extends Controller
{

    private StoredItemTripHistoryService $tripHistoryService;
    private ItemsStorageHistoryService $storageHistoryService;

    public function __construct(
        StoredItemTripHistoryService $tripHistoryService,
        ItemsStorageHistoryService $storageHistoryService
    )
    {
        $this->tripHistoryService = $tripHistoryService;
        $this->storageHistoryService = $storageHistoryService;

        $this->middleware('auth');
        $adminOnly = ['editLoaded', 'updateLoaded', 'editUnloaded', 'updateUnloaded', 'exchangeItems', 'changeItemsTrip'];
        $this->middleware('roles.deny:client,cashier,driver')->except($adminOnly);
        $this->middleware('roles.allow:manager,admin,storekeeper')->only($adminOnly);
    }

    public function associateToTrip(Trip $trip)
    {
//        $data = new \stdClass();
//        $data->trip = $trip;
//        $data->storedItems = request()->storedItems;
//        $data->employee = auth()->user();
//        $writer = new AssociateToTripRequestWriter($data);
//        $writer->write();

        $service = new AssociateItemsToTripsService($this->storageHistoryService, $this->tripHistoryService);
        $service->associate($trip, collect(request()->get('storedItems')));

        return $trip;
    }

    public function editLoaded(Trip $trip)
    {
        $trip->load('unloadedItems.info.item',
            'unloadedItems.info.tariff',
            'unloadedItems.info.owner',
            'unloadedItems.storageHistory.storage',
            'car');
        return view('trips.load-items', compact('trip'));
    }

    public function updateLoaded(Trip $trip)
    {
        $service = new LoadTripItemsService($this->tripHistoryService, $this->storageHistoryService);
        $service->load($trip, collect(request()->get('storedItems')));
    }

    private function getTripItemsFromRequest(Trip $trip)
    {
        return StoredItem::whereHas('tripHistory', function (Builder $query) use ($trip) {
            $query->where('trip_id', $trip->id);
        })->whereIn('id', request()->get('storedItems'))->get();
    }

    public function editUnloaded(Trip $trip)
    {
        $trip->load('loadedItems.info.item',
            'loadedItems.info.tariff',
            'loadedItems.info.owner',
            'loadedItems.storageHistory.storage',
            'car');
        $branches = new Collection([$trip->departureBranch, $trip->destinationBranch]);
        if (auth()->user()->hasRole('admin'))
            $branches = Branch::all();

        return view('trips.unload-items', compact('trip', 'branches'));
    }

    public function updateUnloaded(Trip $trip)
    {
        $data = new \stdClass();
        $data->storedItems = $this->getTripItemsFromRequest($trip);
        $data->branch = Branch::findOrFail(request()->input('branch'));
        $data->employee = auth()->user();

        $writer = new UnloadItemsFromCarRequestWriter($data);
        $writer->write();

        return;
    }

    public function edit(Trip $trip)
    {
        $trip->load('storedItems.info.item', 'storedItems.info.tariff', 'storedItems.info.owner', 'storedItems.storageHistory.storage', 'car');
        $branches = new Collection();
        if (auth()->user()->hasRole('admin'))
            $branches = Branch::all();
        else
            $branches->push(auth()->user()->branch);

        return view('trips.edit-items-list', compact('trip', 'branches'));
    }

    public function exchangeItems(Trip $trip)
    {

        $data = new \stdClass();
        $data->trip = $trip;
        $data->targetTrip = Trip::findOrFail(request()->get('targetTrip'));
        $data->employee = auth()->user();
        $data->storedItems = $this->getTripItemsFromRequest($trip);

        $writer = new ChangeItemsTripRequest($data);
        $writer->write();
    }

    public function changeItemsTrip(Trip $trip)
    {
        $trips = Trip::where('status', '!=', 'finished')->get();
        $trips = $trips->reject(function ($item) use ($trip) {
            return $trip->id === $item->id;
        });
        $trip->load('loadedItems.info.item', 'loadedItems.info.tariff', 'loadedItems.info.owner', 'loadedItems.storageHistory.storage');
        return view('trips.change-items-trip', compact('trip', 'trips'));
    }

    public function generate(Trip $trip)
    {
        $trip->load('storedItems');
        $query = StoredItem::with('info')->available();
        if (request('branch')) {
            $branch = request('branch');
            $storage = Storage::whereHas('branch', function (Builder $query) use ($branch) {
                $query->where('id', $branch);
            })->first();

            $query->storage($storage->id);
        }

        $availableItems = $query->get();

        $generator = new GenerateTripStoredItemListHelper($trip, $availableItems);

        $generatedItemsList = $generator->generate();

        $generatedItemsList = $generatedItemsList->merge($trip->storedItems);

        $generatedItemsList->loadMissing('info.owner', 'info.item', 'info.tariff', 'storageHistory.storage');

        return $generatedItemsList;
    }

    public function availableItems()
    {
        $paginate = request()->input('paginate') ?? 10;
        return StoredItem::available()->with('info.owner', 'info.item', 'info.tariff', 'storageHistory.storage')->paginate($paginate);
    }

    public function availableItemsAtBranch(Branch $branch)
    {
        $paginate = request()->input('paginate') ?? 10;
        return StoredItem::with('info.owner', 'info.item', 'info.tariff', 'storageHistory.storage')->available()->whereHas('storage', function (Builder $query) use ($branch) {
            $query->where('branch_id', $branch->id)->where('deleted_at', null);
        })->paginate($paginate);
//        return $branch->stores()->first()->storedItems()->available()->with('info.owner', 'info.item', 'info.tariff', storageHistory.storage')->paginate($paginate);
    }
}
