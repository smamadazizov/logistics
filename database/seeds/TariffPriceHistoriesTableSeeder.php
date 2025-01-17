<?php /** @noinspection ALL */

use App\Models\Branch;
use App\Models\Tariff;
use App\Models\TariffPriceHistory;
use Illuminate\Database\Seeder;

class TariffPriceHistoriesTableSeeder extends Seeder
{
    private $tariffs = [
        [
            "Обувь-У",
            "12-15-19",
            "90",
            "120",
            "200",
            "200",
            "10",
            "5",
            "0.33",
            "0.93",
            "224.1",
            "28000",
            "125",
            "25990",
            "Урумчи"
        ],
        [
            "Пресс-У",
            "11-01-19",
            "90",
            "120",
            "200",
            "200",
            "10",
            "5",
            "0.33",
            "1",
            "224.1",
            "28000",
            "125",
            "25990",
            "Урумчи"
        ],
        [
            "Запчасти-У",
            "11-01-19",
            "90",
            "120",
            "170",
            "200",
            "10",
            "5",
            "0.33",
            "0.97",
            "224.1",
            "28000",
            "125",
            "27227.5",
            "Урумчи"
        ],
        [
            "Дог-Запч-У",
            "11-01-19",
            "90",
            "120",
            "170",
            "195",
            "5",
            "0",
            "0.33",
            "0.77",
            "224.1",
            "28000",
            "125",
            "26602.5",
            "Урумчи"
        ],
        [
            "Договорной-У",
            "11-01-19",
            "90",
            "120",
            "200",
            "195",
            "5",
            "0",
            "0.33",
            "0.91",
            "224.1",
            "28000",
            "125",
            "25365",
            "Урумчи"
        ],
        [
            "Обувь-И",
            "11-01-19",
            "90",
            "120",
            "200",
            "210",
            "10",
            "5",
            "0.33",
            "0.97",
            "224.1",
            "28000",
            "125",
            "27240",
            "ИВУ"
        ],
        [
            "Пресс-И",
            "11-01-19",
            "90",
            "120",
            "200",
            "200",
            "10",
            "5",
            "0.33",
            "0.93",
            "224.1",
            "28000",
            "125",
            "25990",
            "ИВУ"
        ],
        [
            "Запчасти-И",
            "11-01-19",
            "90",
            "120",
            "170",
            "210",
            "10",
            "5",
            "0.33",
            "1.03",
            "224.1",
            "28000",
            "125",
            "28000",
            "ИВУ"
        ],
        [
            "Дог.Запчасти-И",
            "11-01-19",
            "90",
            "120",
            "170",
            "205",
            "5",
            "0",
            "0.33",
            "0.99",
            "224.1",
            "28000",
            "125",
            "27852.5",
            "ИВУ"

        ],
        [
            "Договорной-И",
            "11-01-19",
            "90",
            "120",
            "200",
            "205",
            "5",
            "0",
            "0.33",
            "0.95",
            "224.1",
            "28000",
            "125",
            "26615",
            "ИВУ"

        ],
        [
            "Обувь-Г",
            "12-01-19",
            "90",
            "120",
            "200",
            "220",
            "10",
            "5",
            "0.33",
            "1.02",
            "224.1",
            "28000",
            "125",
            "28490",
            "Урумчи"
        ],
        [
            "Пресс-Г",
            "12-01-19",
            "90",
            "120",
            "200",
            "220",
            "10",
            "5",
            "0.33",
            "1.02",
            "224.1",
            "28000",
            "125",
            "28490",
            "Урумчи"
        ],
        [
            "Запчасти-Г",
            "12-01-19",
            "90",
            "120",
            "170",
            "220",
            "10",
            "5",
            "0.33",
            "1.06",
            "224.1",
            "28000",
            "125",
            "29727.5",
            "Урумчи"
        ],
        [
            "Дог.Запчасти-Г",
            "12-01-19",
            "90",
            "120",
            "170",
            "215",
            "5",
            "0",
            "0.33",
            "1.04",
            "224.1",
            "28000",
            "125",
            "29102.5",
            "Урумчи"
        ],
        [
            "Договоррной-Г",
            "12-01-19",
            "90",
            "120",
            "200",
            "210",
            "5",
            "0",
            "0.33",
            "0.97",
            "224.1",
            "28000",
            "125",
            "27240",
            "Урумчи"

        ],
        [
            "Обувь-К",
            "11-01-19",
            "90",
            "120",
            "200",
            "145",
            "10",
            "5",
            "0.33",
            "0.65",
            "250",
            "27000",
            "108",
            "17442",
            "Кашкар"
        ],
        [
            "Пресс-К",
            "11-01-19",
            "90",
            "120",
            "200",
            "145",
            "10",
            "5",
            "0.33",
            "0.55",
            "200",
            "22000",
            "110",
            "15950",
            "Кашкар"
        ],
        [
            "Запчасти-К",
            "11-01-19",
            "90",
            "120",
            "170",
            "145",
            "10",
            "5",
            "0.33",
            "0.69",
            "250",
            "27000",
            "108",
            "18511",
            "Кашкар"
        ],
        [
            "Дог.Запчасти-К",
            "11-01-19",
            "90",
            "120",
            "170",
            "140",
            "10",
            "5",
            "0.33",
            "0.67",
            "250",
            "27000",
            "108",
            "17971",
            "Кашкар"
        ],
        [
            "Договороной-К",
            "11-01-19",
            "90",
            "120",
            "200",
            "140",
            "10",
            "5",
            "0.33",
            "0.50",
            "200",
            "22000",
            "110",
            "15400",
            "Кашкар"


        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $branchId = Branch::first()->id;
//        $this->tariffs = Tariff::all();

//        $newPrice = new TariffPriceHistory();
//        $newPrice->lowerLimit = 90;
//        $newPrice->mediumLimit = 120;
//        $newPrice->upperLimit = 200;
//        $newPrice->discountForLowerLimit = 5;
//        $newPrice->discountForMediumLimit =0;
//        $newPrice->pricePerCube = 165;
//        $newPrice->agreedPricePerKg =0.73;
//        $newPrice->pricePerExtraKg =0.33;
//        $newPrice->maxWeightPerCube = 250;
//        $newPrice->maxCubage = 108;
//        $newPrice->maxWeight = 27000;
//        $newPrice->branch_id = $branchId;
//        $newPrice->tariff_id = $this->getId('Обувь-У');
//        $newPrice->totalMoney = 19602;
//        $newPrice->save();

        foreach ($this->tariffs as $tariffData) {
            $tariff = Tariff::where('name', $tariffData[0])->first();
            if ($tariff) {
                TariffPriceHistory::create([
                    'lowerLimit' => $tariffData[2],
                    'mediumLimit' => $tariffData[3],
                    'upperLimit' => $tariffData[4],
                    'pricePerCube' => $tariffData[5],
                    'discountForLowerLimit' => $tariffData[6],
                    'discountForMediumLimit' => $tariffData[7],
                    'pricePerExtraKg' => $tariffData[8],
                    'agreedPricePerKg' => $tariffData[9],
                    'maxWeightPerCube' => $tariffData[10],
                    'maxWeight' => $tariffData[11],
                    'maxCubage' => $tariffData[12],
                    'totalMoney' => $tariffData[13],
//                    'branch_id' => $branchId,
                    'tariff_id' => $tariff->id
                ]);
            }
        }


//        $tariff = $this->getId('Запчасти-У');
//        TariffPriceHistory::create([
//            'lowerLimit' => 90,
//            'mediumLimit' => 120,
//            'upperLimit' => 170,
//            'discountForLowerLimit' => 10,
//            'discountForMediumLimit' => 5,
//            'pricePerCube' => 170,
//            'agreedPricePerKg' =>0.79,
//            'pricePerExtraKg' =>0.33,
//            'maxWeightPerCube' => 250,
//            'maxCubage' => 108,
//            'maxWeight' => 27000,
//            'branch_id' => $branchId,
//            'tariff_id' => $tariff,
//            'totalMoney' => 21211
//        ]);
//
//        $tariff = $this->getId('Договорной-У');
//        TariffPriceHistory::create([
//            'lowerLimit' => 110,
//            'mediumLimit' => 170,
//            'upperLimit' => 200,
//            'discountForLowerLimit' => 10,
//            'discountForMediumLimit' => 5,
//            'pricePerCube' => 170,
//            'agreedPricePerKg' =>0.67,
//            'pricePerExtraKg' =>0.33,
//            'maxWeightPerCube' => 250,
//            'maxCubage' => 108,
//            'maxWeight' => 27000,
//            'branch_id' => $branchId,
//            'tariff_id' => $tariff,
//            'totalMoney' => 21306.96
//        ]);

//        $tariff=$this->getId('Кашкар');
//        TariffPriceHistory::create([
//            'lowerLimit'=>110,
//            'mediumLimit'=>170,
//            'upperLimit'=>200,
//            'discountForLowerLimit'=>10,
//            'discountForMediumLimit'=>5,
//            'pricePerCube'=>155,
//            'agreedPricePerKg'=>0.6,
//            'pricePerExtraKg'=>0.33,
//            'maxWeightPerCube'=>250,
//            'maxCubage'=>108,
//            'maxWeight'=>27000,
//            'branch_id'=>$branchId,
//            'tariff_id'=>$tariff,
//            'totalMoney' => 21306.96
//        ]);
    }

//    private function getId($tariffName)
//    {
//        foreach ($this->tariffs as $tariff) {
//            if ($tariff->name === $tariffName)
//                return $tariff->id;
//        }
//        return null;
//    }
}
