<?php

namespace Services;

use App\Exceptions\RecordNotFoundException;
use App\Helpers\Arr;
use App\Helpers\DBCleanup;
use App\Repositories\VehicleFeaturesRepository;
use App\Services\VehicleService;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class VehicleServiceTest extends TestCase
{
    /**
     * @var VehicleService|null
     */
    protected ?VehicleService $vehicleService;
    /**
     * @var Generator|null
     */
    protected ?Generator $factory;

    protected function setUp(): void
    {
        $this->vehicleService = new VehicleService();
        $this->factory = Factory::create();
    }

    protected function tearDown(): void
    {
        $this->vehicleService = null;
        $this->factory = null;
        DBCleanup::cleanCarFeatures();
        DBCleanup::cleanVehicles();
        DBCleanup::cleanbrands();
        DBCleanup::cleanCarModels();
        DBCleanup::cleanLocations();
    }

    public function testStoreCar(): void
    {
        $vehicleId = $this->vehicleService->storeCar($this->rawUserInput());
        $this->assertIsNumeric($vehicleId);
        $features = (new VehicleFeaturesRepository())->findOne(['vehicle_id' => $vehicleId]);
        $this->assertIsArray($features);
    }


    public function testSearchCar(): void
    {
        $input = $this->rawUserInput();
        $this->vehicleService->storeCar($input);
        $conditions = Arr::only($input, [
                'car_model',
                'car_brand',
                'location',
                'license_plate',
                'fuel_type',
                'transmission',
                'car_type'
            ]
        );

        $conditions['limit'] = 1;
        $conditions['offset'] = 0;
        $searchResult = $this->vehicleService->searchCar($conditions);
        $this->assertIsArray($searchResult);
        $this->assertCount(1, $searchResult);
    }


    public function testFind(): void
    {
        $input = $this->rawUserInput();
        $vehicleId = $this->vehicleService->storeCar($input);
        $searchResult = $this->vehicleService->find($vehicleId);
        $this->assertIsArray($searchResult);
        $this->assertArrayHasKey('license_plate', $searchResult);
    }

    /**
     * @return void
     * @throws RecordNotFoundException
     */
    public function testFindException(): void
    {
        $this->expectException(RecordNotFoundException::class);
        $this->vehicleService->find(-1);
    }

    /**
     * @return array
     */
    private function rawUserInput(): array
    {
        return [
            'location' => $this->factory->realTextBetween(6, 10) . time(),
            'car_brand' => $this->factory->realTextBetween(6, 10) . time(),
            'car_model' => $this->factory->realTextBetween(6, 10) . time(),
            'license_plate' => $this->factory->unique()->text(6) . '-' . time(),
            'car_year' => $this->factory->year(),
            'number_of_doors' => $this->factory->randomDigit(),
            'number_of_seats' => $this->factory->randomDigit(),
            'fuel_type' => $this->factory->realTextBetween(6, 10),
            'transmission' => $this->factory->realTextBetween(6, 10),
            'car_type_group' => $this->factory->realTextBetween(6, 10),
            'car_type' => $this->factory->realTextBetween(6, 10),
            'car_km' => $this->factory->numberBetween(0, 20000),
            'inside_height' => $this->factory->randomFloat(1, 20),
            'inside_length' => $this->factory->optional()->randomFloat(1, 20),
            'inside_width' => $this->factory->optional()->randomFloat(1, 20),
        ];
    }
}
