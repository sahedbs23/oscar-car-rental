<?php

namespace Repositories;

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
    }

    /**
     * @return void
     */
    public function testCreateCarLocation(): void
    {
        $locationName = $this->faker->text(90);
        $brandId = $this->repository->createCarLocation($locationName);
        $this->assertIsInt($brandId);

        $res = $this->repository->createCarLocation($locationName, true);
        $this->assertIsArray($res);
        $this->assertArrayHasKey('location', $res);

        $this->assertTrue($this->repository->delete(['id' => $brandId]));

        $res = $this->repository->createCarLocation($this->faker->realTextBetween(120, 150));
        $this->assertFalse($res);
    }
}
