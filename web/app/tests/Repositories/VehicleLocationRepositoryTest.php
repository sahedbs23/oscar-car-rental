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

    protected function tearDown(): void
    {
        $this->repository = null;
        $this->faker = null;
        DBCleanup::cleanLocations();
    }

    /**
     * @return void
     */
    public function testCreateCarLocation(): void
    {
        $locationName = $this->faker->text(90);
        $brandId = $this->repository->createCarLocation($locationName);
        $this->assertIsInt($brandId);
    }

    /**
     * @return void
     */
    public function testUpdateOrCreate(): void
    {
        $locationName = $this->faker->text(90);
        $res = $this->repository->updateOrCreate($locationName);
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
