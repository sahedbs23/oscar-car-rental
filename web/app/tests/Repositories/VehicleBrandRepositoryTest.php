<?php

namespace Repositories;

use App\Helpers\DBCleanup;
use App\Repositories\VehicleBrandRepository;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class VehicleBrandRepositoryTest extends TestCase
{
    /**
     * @var VehicleBrandRepository|null
     */
    public ?VehicleBrandRepository $repository;
    /**
     * @var Generator|null
     */
    public ?Generator $faker;

    protected function setUp(): void
    {
        $this->repository = new VehicleBrandRepository();
        $this->faker = Factory::create();
    }

    protected function tearDown(): void
    {
        $this->repository = null;
        $this->faker = null;
        DBCleanup::cleanbrands();
    }

    /**
     * @return void
     */
    public function testCreateBrand(): void
    {
        $brandName = $this->faker->text(90);
        $brandId = $this->repository->createBrand($brandName);
        $this->assertIsInt($brandId);
    }

    /**
     * @return void
     */
    public function testFindOrCreateBrand(): void
    {
        $brandName = $this->faker->text(90);
        $res = $this->repository->findOrCreateBrand($brandName);
        $this->assertIsArray($res);
        $this->assertArrayHasKey('brand_name', $res);
    }
}
