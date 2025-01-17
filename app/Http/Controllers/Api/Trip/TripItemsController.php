<?php
/**
 * @author Sultonazar Mamadazizov <sultonazar.mamadazizov@mail.ru>
 */

namespace App\Http\Controllers\Api\Trip;


use App\Http\Controllers\Controller;
use App\Models\StoredItems\StoredItem;
use App\Models\StoredItems\StoredItemTripHistory;
use App\Models\Trip;
use App\Services\Trip\TripService;

class TripItemsController extends Controller
{
    public function __construct(TripService $tripService)
    {
        $this->middleware('auth:api');
        $this->middleware('roles.allow:employee');
    }

    public function index(Trip $trip)
    {
        return $trip->storedItems()
            ->wherePivotIn('status', [
                StoredItemTripHistory::STATUS_LISTED,
                StoredItemTripHistory::STATUS_LOADED,
            ])
            ->select(['stored_items.id', 'stored_item_info_id', 'code', 'stored_items.status'])
            ->with(['info' => function ($query) {
                $query->select(['id', 'shop', 'count', 'weight', 'height', 'length', 'width', 'owner_id'])
                    ->with(['owner' => function ($query) {
                        $query->select(['id', 'code']);
                    }]);
            }])
            ->whereIn('stored_items.status', [
                StoredItem::STATUS_STORED,
                StoredItem::STATUS_TRANSIT
            ])->get()->map(function (StoredItem $storedItem) {
                $storedItem->trip_status = $storedItem->tripHistory->status;
                $storedItem->trip_id = $storedItem->tripHistory->trip_id;
                $storedItem->info->owner_code = $storedItem->info->owner->code;
                $storedItem->unsetRelation('tripHistory');
                $storedItem->info->unsetRelation('owner');
                return $storedItem;
            });
    }
}
