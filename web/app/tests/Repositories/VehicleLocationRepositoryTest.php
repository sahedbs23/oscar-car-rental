<?php

namespace Repositories;

use App\Helpers\DBCleanup;
use App\Repositories\VehicleLocationRepository;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class VehicleLocationRepositoryTest extends TestCase
{
    /**
     * @var VehicleLocationRepository|null
     */
    public ?VehicleLocationRepository $repository;
    /**
     * @var Generator|null
     */
    public ?Generator $faker;

    protected function setUp(): void
    {
        $this->repository = new VehicleLocationRepository();
        $this->faker = Factory::create();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->repository = null;
        $this->faker = null;
        DBCleanup::cleanCarFeatures();
        DBCleanup::cleanVehicles();
        DBCleanup::cleanLocations();
    }

    /**
     * @return void
     */
    public function testCreateCarLocation(): void
    {
        $locationName = $this->faker->realTextBetween(5, 10) . time();
        $locationID = $this->repository->createCarLocation($locationName);
        $this->assertIsInt($locationID);
    }

    /**
     * @return void
     */
    public function testReturnExistingOne(): void
    {
        $locationName = $this->faker->realTextBetween(5, 10) . time();
        $locationID = $this->repository->createCarLocation($locationName);
        $locationExisting = $this->repository->createCarLocation($locationName);
        $this->assertEquals($locationID, $locationExisting);
    }

    /**
     * @return void
     */
    public function testUpdateOrCreate(): void
    {
        $locationName = $this->faker->realTextBetween(5, 10) . time();
        $locationID = $this->repository->createCarLocation($locationName);
        $res = $this->repository->findLocationById($locationID);
        $this->assertIsArray($res);
        $this->assertArrayHasKey('location', $res);
    }

    /**
     * @return void
     */
    public function testCreateCarLocationException(): void
    {
        $res = $this->repository->createCarLocation($this->faker->realTextBetween(120, 150));
        $this->assertFalse($res);
    }
}
