<?php /** @noinspection DuplicatedCode */

namespace Tests\Feature;

use App\Common\PasswordGenerator;
use App\Models\BillingInfo;
use App\Models\Branch;
use App\Models\Branches\Storage;
use App\Models\Customs\CustomsCode;
use App\Models\Customs\CustomsCodeTax;
use App\Models\Order;
use App\Models\Role;
use App\Models\StoredItems\Item;
use App\Models\StoredItems\StorageHistory;
use App\Models\StoredItems\StoredItem;
use App\Models\StoredItems\StoredItemInfo;
use App\Models\Tariff;
use App\Models\TariffPriceHistory;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 *
 * @author Sultonazar Mamadazizov <sultonazar.mamadazizov@mail.ru> on 03.07.2020
 */
class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_store()
    {
        $data = $this->prepareMockData();

        $this->withoutMiddleware();
        $this->withoutExceptionHandling();

        $itemsCount = 10;

        $response = $this->actingAs($data['employee'])
            ->post('/orders', [
                'clientCode' => $data['client']->code,
                'branch_id' => $data['branch']->id,
                'storedItemInfos' => [
                    0 => [
                        'width' => 0.25,
                        'height' => 0.55,
                        'length' => 0.65,
                        'weight' => 3,
                        'shop' => 'Test shop',
                        'count' => $itemsCount,
                        'placeCount' => $itemsCount,
                        'item_id' => $data['item']->id,
                        'tariff_id' => $data['tariff']->id,
                        'branch_id' => $data['branch']->id,
                        'customs_code_id' => $data['customsCode']->id
                    ]
                ],
                'customPrices' => [null]
            ]);

        $response->assertStatus(201);

        $this->assertCount(1, Order::all());

        $this->assertCount(1, StoredItemInfo::all());

        $orderIds = StoredItemInfo::with('order')->get()->pluck('order.id')->unique();

        $this->assertCount(1, $orderIds);

        $this->assertEquals(Order::first()->id, $orderIds->first());

        $this->assertCount(1, BillingInfo::all());

        $this->assertCount(100, StoredItem::all());

        $this->assertCount(100, StorageHistory::all());

        $statuses = StoredItem::all()->pluck('status')->unique();

        $this->assertCount(1, $statuses);

        $this->assertEquals('stored', $statuses->first());
    }

    public function test_order_update()
    {
        $data = $this->prepareMockData();

        $this->withoutMiddleware();
//        $this->withoutExceptionHandling();

        $tariff = Tariff::create([
            'name' => 'Мешок',
            'branch_id' => $data['branch']->id
        ]);

        $tariffPricing = TariffPriceHistory::create([
            'created_at' => '2020-07-05 08:03:11',
            'lowerLimit' => 200,
            'mediumLimit' => 200,
            'upperLimit' => 200,
            'discountForLowerLimit' => 200,
            'discountForMediumLimit' => 200,
            'pricePerCube' => 200,
            'agreedPricePerKg' => 200,
            'pricePerExtraKg' => 200,
            'maxWeightPerCube' => 200,
            'maxCubage' => 200,
            'maxWeight' => 200,
            'totalMoney' => 200,
            'tariff_id' => $tariff->id
        ]);

        $storeResponse = $this->actingAs($data['employee'])
            ->post('/orders', [
                'clientCode' => $data['client']->code,
                'branch_id' => $data['branch']->id,
                'storedItemInfos' => [
                    0 => [
                        'width' => 0.25,
                        'height' => 0.55,
                        'length' => 0.65,
                        'weight' => 3,
                        'shop' => 'Test shop',
                        'count' => 10,
                        'placeCount' => 20,
                        'item_id' => $data['item']->id,
                        'tariff_id' => $data['tariff']->id,
                        'branch_id' => $data['branch']->id,
                        'customs_code_id' => $data['customsCode']->id
                    ],
                    1 => [
                        'width' => 0.3,
                        'height' => 0.75,
                        'length' => 0.98,
                        'weight' => 5,
                        'shop' => 'Test shop 2',
                        'count' => 20,
                        'placeCount' => 20,
                        'item_id' => $data['item']->id,
                        'tariff_id' => $data['tariff']->id,
                        'branch_id' => $data['branch']->id,
                        'customs_code_id' => $data['customsCode']->id
                    ],
                ],
                'customPrices' => [null, null]
            ]);

        $storeResponse->assertStatus(201);

        $order = Order::with('storedItemInfos')->first();

        $putData = [
            'clientCode' => $data['client']->code,
            'branch_id' => $data['branch']->id,
            'storedItemInfos' => [
                0 => [
                    'id' => $order->storedItemInfos[0]->id,
                    'width' => 0.3,
                    'height' => 0.4,
                    'length' => 0.5,
                    'weight' => 8,
                    'shop' => 'Test shop',
                    'count' => 10,
                    'placeCount' => 15,
                    'item_id' => $data['item']->id,
                    'tariff_id' => $data['tariff']->id,
                    'branch_id' => $data['branch']->id,
                    'customs_code_id' => $data['customsCode']->id
                ],
                1 => [
                    'id' => $order->storedItemInfos[1]->id,
                    'width' => 0.3,
                    'height' => 0.75,
                    'length' => 0.98,
                    'weight' => 5,
                    'shop' => 'Test shop 2',
                    'count' => 10,
                    'placeCount' => 10,
                    'item_id' => $data['item']->id,
                    'tariff_id' => $tariff->id,
                    'branch_id' => $data['branch']->id,
                    'customs_code_id' => $data['customsCode']->id
                ]
            ],
            'customPrices' => [null, 92.5]
        ];

        $response = $this->actingAs($data['employee'])->put('/orders/' . $order->id, $putData);

        $response->assertOk();

        $this->assertCount(1, Order::all());

        $this->assertCount(2, StoredItemInfo::all());

        $this->assertCount(250, StoredItem::all());

        $this->assertCount(350, StoredItem::onlyTrashed()->get());

        $this->assertCount(1, StoredItem::onlyTrashed()->pluck('status')->unique());

        $this->assertEquals(StoredItem::STATUS_DELETED, StoredItem::onlyTrashed()->pluck('status')->first());

        $this->assertCount(250, StorageHistory::all());

        $this->assertCount(600, StorageHistory::onlyTrashed()->get());

        $this->assertEquals($data['employee']->id, StorageHistory::onlyTrashed()->first()->destroyer->id);
    }

    public function test_order_destroy()
    {
        $data = $this->prepareMockData();

        $this->withoutExceptionHandling();

        $itemsCount = 10;

        $adminRole = Role::create([
            'name' => 'admin',
            'title' => 'Администратор',
            'description' => 'админ'
        ]);

        $data['employee']->roles()->sync($adminRole);

        $storeResponse = $this->actingAs($data['employee'])
            ->post('/orders', [
                'clientCode' => $data['client']->code,
                'branch_id' => $data['branch']->id,
                'storedItemInfos' => [
                    0 => [
                        'width' => 0.25,
                        'height' => 0.55,
                        'length' => 0.65,
                        'weight' => 3,
                        'shop' => 'Test shop',
                        'count' => $itemsCount,
                        'placeCount' => $itemsCount,
                        'item_id' => $data['item']->id,
                        'tariff_id' => $data['tariff']->id,
                        'branch_id' => $data['branch']->id,
                        'customs_code_id' => $data['customsCode']->id
                    ]
                ],
                'customPrices' => [null]
            ]);

        $storeResponse->assertStatus(201);

        $response = $this->actingAs($data['employee'])->delete('/orders/' . Order::first()->id);

        $response->assertStatus(200);

        $this->assertCount(0, Order::all());

        $this->assertCount(1, Order::onlyTrashed()->get());

        $this->assertCount(0, StoredItemInfo::all());

        $this->assertCount(1, StoredItemInfo::onlyTrashed()->get());

        $this->assertEquals(StoredItemInfo::STATUS_DELETED, StoredItemInfo::onlyTrashed()->first()->status);

        $this->assertCount(0, StoredItem::all());

        $this->assertCount(100, StoredItem::onlyTrashed()->get());

        $this->assertEquals(StoredItem::STATUS_DELETED, StoredItem::onlyTrashed()->pluck('status')->first());

        $this->assertCount(0, StorageHistory::all());

        $this->assertCount(100, StorageHistory::onlyTrashed()->get());

        $this->assertEquals($data['employee']->id, StorageHistory::onlyTrashed()->first()->destroyer->id);

        $this->assertCount(0, BillingInfo::all());

        $this->assertCount(1, BillingInfo::onlyTrashed()->get());
    }

    public function test_order_delete_stored_item()
    {
        $data = $this->prepareMockData();

        $this->withoutMiddleware();

        $storeResponse = $this->actingAs($data['employee'])
            ->post('/orders', [
                'clientCode' => $data['client']->code,
                'branch_id' => $data['branch']->id,
                'storedItemInfos' => [
                    0 => [
                        'width' => 0.25,
                        'height' => 0.55,
                        'length' => 0.65,
                        'weight' => 3,
                        'shop' => 'Test shop',
                        'count' => 1,
                        'placeCount' => 10,
                        'item_id' => $data['item']->id,
                        'tariff_id' => $data['tariff']->id,
                        'branch_id' => $data['branch']->id,
                        'customs_code_id' => $data['customsCode']->id
                    ],
                ],
                'customPrices' => [null, null]
            ]);

        $storeResponse->assertStatus(201);

        $order = Order::with('storedItemInfos')->first();

        $putData = [
            'clientCode' => $data['client']->code,
            'branch_id' => $data['branch']->id,
            'storedItemInfos' => [
                0 => [
                    'width' => 0.3,
                    'height' => 0.75,
                    'length' => 0.98,
                    'weight' => 5,
                    'shop' => 'Test shop 2',
                    'count' => 1,
                    'placeCount' => 5,
                    'item_id' => $data['item']->id,
                    'tariff_id' => $data['tariff']->id,
                    'branch_id' => $data['branch']->id,
                    'customs_code_id' => $data['customsCode']->id
                ]
            ],
            'customPrices' => [null]
        ];

        $response = $this->put('/orders/' . $order->id, $putData);

        $response->assertOk();

        $this->assertCount(1, Order::all());

        $this->assertCount(1, StoredItemInfo::all());

        $this->assertCount(1, StoredItemInfo::onlyTrashed()->get());

        $this->assertEquals(StoredItemInfo::STATUS_DELETED, StoredItemInfo::onlyTrashed()->first()->status);

        $this->assertCount(5, StoredItem::all());

        $this->assertCount(10, StoredItem::onlyTrashed()->get());

        $this->assertEquals(StoredItem::STATUS_DELETED, StoredItem::onlyTrashed()->pluck('status')->first());

        $this->assertEquals($data['employee']->id, StorageHistory::onlyTrashed()->first()->destroyer->id);
    }

    private function prepareMockData()
    {
        $data = [];
        $data['branch'] = Branch::create([
            'name' => 'Душанбе',
            'country' => '01ec7gk3ac4ekfde5vfy5p0s23'
        ]);

        $data['storage'] = Storage::create([
            'branch_id' => $data['branch']->id,
            'name' => 'Ленинский'
        ]);

        $data['client'] = User::create([
            'code' => '123456',
            'password' => Hash::make(PasswordGenerator::generate()),
            'branch_id' => $data['branch']->id,
        ]);

        $data['employee'] = User::create([
            'code' => '52525',
            'password' => Hash::make(PasswordGenerator::generate()),
            'branch_id' => $data['branch']->id,
        ]);


        $data['item'] = Item::create([
            'name' => 'Одежда',
            'calculateByNormAndWeight' => true,
            'applyDiscount' => true
        ]);

        $data['tariff'] = Tariff::create([
            'name' => 'Одежда',
            'branch_id' => $data['branch']->id,
        ]);

        $data['tariffPricing'] = TariffPriceHistory::create([
            'created_at' => '2020-07-05 08:03:11',
            'lowerLimit' => 100,
            'mediumLimit' => 100,
            'upperLimit' => 100,
            'discountForLowerLimit' => 100,
            'discountForMediumLimit' => 100,
            'pricePerCube' => 100,
            'agreedPricePerKg' => 100,
            'pricePerExtraKg' => 100,
            'maxWeightPerCube' => 100,
            'maxCubage' => 100,
            'maxWeight' => 100,
            'totalMoney' => 100,
            'tariff_id' => $data['tariff']->id
        ]);

        $data['customsCode'] = CustomsCode::create([
            'name' => 'Одежда',
            'code' => '15755484'
        ]);

        $data['customsCodeTax'] = CustomsCodeTax::create([
            'customs_code_id' => $data['customsCode']->id,
            'price' => 100,
            'interestRate' => 5,
            'vat' => 18,
            'totalRate' => 22.5,
            'isCalculatedByPiece' => false
        ]);

        $this->withoutExceptionHandling();

        return $data;
    }
}
