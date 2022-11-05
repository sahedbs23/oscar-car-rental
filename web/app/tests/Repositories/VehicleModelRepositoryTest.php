<?php

namespace Repositories;

use App\Repositories\VehicleModelRepository;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class VehicleModelRepositoryTest extends TestCase
{
    public ?VehicleModelRepository $repository;
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
    }

    /**
     * @return void
     */
    public function testCreateBrand():void
    {
        $modelName = $this->faker->text(90);
        $model = $this->repository->createModel($modelName, true);
        $this->assertIsArray($model);
        $this->assertArrayHasKey('car_model', $model);

        $modelId = $this->repository->createModel($modelName);
        $this->assertIsInt($modelId);

        $this->assertTrue($this->repository->delete(['id'=>$modelId]));

    }
}
