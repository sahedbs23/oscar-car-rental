<?php

namespace Repositories;

use App\Helpers\ConfigDatabase;
use App\Helpers\DBCleanup;
use App\Repositories\VehicleFeaturesRepository;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class VehicleFeaturesRepositoryTest extends TestCase
{
    public ?VehicleFeaturesRepository $repository;
    public ?Generator $faker;

    protected function setUp(): void
    {
        $this->repository = new VehicleFeaturesRepository();
        $this->faker = Factory::create();
    }

    protected function tearDown(): void
    {
        $this->repository = null;
        $this->faker = null;
        DBCleanup::cleanCarFeatures();
        DBCleanup::cleanVehicles();
        DBCleanup::cleanCarModels();
        DBCleanup::cleanLocations();
        DBCleanup::cleanbrands();
    }

    /**
     * @return void
     */
    public function testSaveCarFeatures():void
    {
        $vehicle_id = ConfigDatabase::setupVehicle();
        $features = [
            'vehicle_id' => $vehicle_id,
            'inside_height' =>$this->faker->randomFloat(),
            'inside_length' =>$this->faker->optional()->randomFloat(),
            'inside_width' =>$this->faker->optional()->randomFloat()
        ];
        $carFeatureId = $this->repository->saveCarFeatures($features);
        $this->assertIsInt($carFeatureId);

        $res = $this->repository->saveCarFeatures($features, true);
        $this->assertIsArray($res);
        $this->assertArrayHasKey('vehicle_id', $res);
    }
}
