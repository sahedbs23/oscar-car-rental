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
    /**
     * @var VehicleFeaturesRepository|null
     */
    public ?VehicleFeaturesRepository $repository;
    /**
     * @var Generator|null
     */
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
    public function testSaveCarFeatures(): void
    {
        $vehicle_id = ConfigDatabase::setupVehicle();
        $features = [
            'vehicle_id' => $vehicle_id,
            'inside_height' => $this->faker->randomFloat(),
            'inside_length' => $this->faker->optional()->randomFloat(),
            'inside_width' => $this->faker->optional()->randomFloat()
        ];
        $carFeatureId = $this->repository->saveCarFeatures($features);
        $this->assertIsInt($carFeatureId);
    }


    /**
     * @return void
     */
    public function testUpdateCarFeatures(): void
    {
        $vehicle_id = ConfigDatabase::setupVehicle();
        $features = [
            'vehicle_id' => $vehicle_id,
            'inside_height' => $this->faker->randomFloat(),
            'inside_length' => $this->faker->optional()->randomFloat(),
            'inside_width' => $this->faker->optional()->randomFloat()
        ];
        $carFeatureId = $this->repository->saveCarFeatures($features);
        $carFeatureId2 = $this->repository->saveCarFeatures($features);
        $this->assertEquals($carFeatureId, $carFeatureId2);
    }

    public function testFindCarFeatureById(): void
    {
        $vehicle_id = ConfigDatabase::setupVehicle();
        $features = [
            'vehicle_id' => $vehicle_id,
            'inside_height' => $this->faker->randomFloat(),
            'inside_length' => $this->faker->optional()->randomFloat(),
            'inside_width' => $this->faker->optional()->randomFloat()
        ];
        $carFeatureId = $this->repository->saveCarFeatures($features);
        $res = $this->repository->findCarFeatureById($carFeatureId);
        $this->assertIsArray($res);
        $this->assertArrayHasKey('vehicle_id', $res);
        $this->assertArrayHasKey('inside_height', $res);
        $this->assertArrayHasKey('inside_length', $res);
        $this->assertArrayHasKey('inside_width', $res);
    }
}
