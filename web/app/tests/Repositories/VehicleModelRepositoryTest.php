<?php

namespace Repositories;

use App\Helpers\DBCleanup;
use App\Repositories\VehicleModelRepository;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class VehicleModelRepositoryTest extends TestCase
{
    /**
     * @var VehicleModelRepository|null
     */
    public ?VehicleModelRepository $repository;
    /**
     * @var Generator|null
     */
    public ?Generator $faker;

    protected function setUp(): void
    {
        $this->repository = new VehicleModelRepository();
        $this->faker = Factory::create();
    }

    protected function tearDown(): void
    {
        $this->repository = null;
        $this->faker = null;
        DBCleanup::cleanCarFeatures();
        DBCleanup::cleanVehicles();
        DBCleanup::cleanCarModels();
    }

    /**
     * @return void
     */
    public function testCreateModel(): void
    {
        $modelName = $this->faker->text(10).time();
        $modelId = $this->repository->createModel($modelName);
        $this->assertIsInt($modelId);
    }

   /**
     * @return void
     */
    public function testReturnExistingOne(): void
    {
        $modelName = $this->faker->text(10).time();
        $createdId = $this->repository->createModel($modelName);
        $updatedId = $this->repository->createModel($modelName);
        $this->assertEquals($createdId, $updatedId);
    }


  /**
     * @return void
     */
    public function testCreateModelFail(): void
    {
        $modelName = $this->faker->realTextBetween(120, 150);
        $result = $this->repository->createModel($modelName);
        $this->assertFalse($result);
    }



    /**
     * @return void
     */
    public function testFindCarModelById(): void
    {
        $modelName = $this->faker->realTextBetween(10, 20).time();
        $carModelId = $this->repository->createModel($modelName);
        $carModel = $this->repository->findCarModelById($carModelId);
        $this->assertIsArray($carModel);
        $this->assertArrayHasKey('car_model', $carModel);
    }
}
