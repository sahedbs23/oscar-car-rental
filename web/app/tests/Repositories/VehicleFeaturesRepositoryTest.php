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

    public function testFindOrCarFeatures(): void
    {
        $vehicle_id = ConfigDatabase::setupVehicle();
        $features = [
            'vehicle_id' => $vehicle_id,
            'inside_height' => $this->faker->randomFloat(),
            'inside_length' => $this->faker->optional()->randomFloat(),
            'inside_width' => $this->faker->optional()->randomFloat()
        ];
        $res = $this->repository->findOrCarFeatures($features);
        $this->assertIsArray($res);
        $this->assertArrayHasKey('vehicle_id', $res);
    }
}
