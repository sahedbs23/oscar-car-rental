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
        DBCleanup::cleanCarFeatures();
        DBCleanup::cleanVehicles();
        DBCleanup::cleanbrands();
    }

    /**
     * @return void
     */
    public function testCreateBrand(): void
    {
        $brandId = $this->createBrand();
        $this->assertIsInt($brandId);
    }

    /**
     * @return void
     */
    public function testCreateBrandFail(): void
    {
        $brandName = $this->faker->realTextBetween(120, 150) . time();
        $brandId = $this->repository->createBrand($brandName);
        $this->assertFalse($brandId);
    }

    /**
     * @return void
     */
    public function testReturnExistingId(): void
    {
        $brandName = $this->faker->realTextBetween(6, 10) . time();
        $brandId = $this->repository->createBrand($brandName);
        $brandIdExisting = $this->repository->createBrand($brandName);
        $this->assertEquals($brandId, $brandIdExisting);
    }

    /**
     * @return void
     */
    public function testFindBranById(): void
    {
        $brandId = $this->createBrand();
        $res = $this->repository->findBranById($brandId);
        $this->assertIsArray($res);
        $this->assertArrayHasKey('brand_name', $res);
    }

    /**
     * @return false|int
     */
    private function createBrand(): int|false
    {
        $brandName = $this->faker->realTextBetween(6, 10) . time();
        return $this->repository->createBrand($brandName);
    }
}
