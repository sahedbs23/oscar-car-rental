<?php

namespace Repositories;

use App\Repositories\BaseRepository;
use App\Repositories\VehicleRepository;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    public null|int $brandId ;
    public null|int $locationId ;
    public null|int $modelId ;
    public null|int $vehicleId ;
    public ?BaseRepository $repository;

    public ?Generator $faker;

    protected function setUp(): void
    {
        $this->repository = new BaseRepository();
        $this->faker = Factory::create();

        $repo = new BaseRepository();
        $repo->setTable('car_brands')
            ->create([
                'brand_name' => $this->faker->unique()->text(90)
            ]);
        $this->brandId = $repo->lastSavedId();

        $repo = new BaseRepository();
        $repo->setTable('car_locations')
            ->create([
                'location' => $this->faker->unique()->text(90)
            ]);
        $this->locationId = $repo->lastSavedId();

        $repo = new BaseRepository();

        $repo->setTable('car_models')
            ->create([
                'car_model' => $this->faker->unique()->text(90)
            ]);
        $this->modelId = $repo->lastSavedId();
    }

    protected function tearDown(): void
    {
        $this->repository = null;
        $this->faker = null;
    }

    public function test__construct():void
    {
        $this->assertEquals('test', $this->repository->setTable('test')->getTable());
        $this->assertEquals('id', $this->repository->getPk());
    }


    /**
     * @return void
     */
    public function testCreate():void
    {
        $res = $this->repository->setTable(VehicleRepository::TABLE_NAME)
       ->create(
           [
              'location' => $this->locationId,
              'car_brand' =>$this->brandId,
              'car_model' => $this->modelId,
              'license_plate' => $this->faker->optional()->text(10),
           ]
       );
        $this->assertTrue($res);
        $this->vehicleId = $this->repository->lastSavedId();
    }

    /**
     * @return void
     */
    public function testCreateFail():void
    {
        $this->expectException(\PDOException::class);
        $this->repository->setTable(VehicleRepository::TABLE_NAME)
       ->create(
           [
              'location' => $this->faker->numberBetween(2000, 50000),
              'car_brand' => $this->faker->numberBetween(2000, 50000),
              'car_model' => $this->faker->numberBetween(2000, 50000),
              'license_plate' => $this->faker->optional()->text(10),
           ]
       );
    }


    /**
     * @return void
     */
    public function testfindById():void
    {
       $record = $this->repository->setTable('car_brands')->findById($this->brandId);
       $this->assertIsArray($record);
    }

    /**
     * @return void
     */
    public function testExists():void
    {
       $record = $this->repository->setTable('car_locations')->exists($this->locationId);
       $this->assertIsArray($record);
    }

    /**
     * @return void
     */
    public function testfindOneInvalid():void
    {
        $this->expectException(\PDOException::class);
        $this->repository->setTable('vehicle')->findById(50000);
    }

    /**
     * @depends testCreate
     * @return void
     */
    public function testFind()
    {
        $res = $this->repository->setTable(VehicleRepository::TABLE_NAME)->findAll(
            [
                'fields'=> [
                   'id', 'location'
                ],
                'conditions' => [
                    'location' => $this->locationId,
                    'car_brand' => $this->brandId,
                    'car_model' => $this->modelId,
                ],
                'order' => 'ID ASC',
                'limit' => 10,
                'offset' => 0
            ]
        );
        $this->assertIsArray($res);
    }
//
//    /**
//     * @depends testCreate
//     * @return void
//     */
//    public function testDeelte()
//    {
//        $res = $this->repository->setTable(VehicleRepository::TABLE_NAME)->delete([
//                'id' => $this->vehicleId
//        ]);
//        $this->assertTrue($res);
//    }
}
