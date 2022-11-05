<?php

namespace Repositories;

use App\Helpers\ConfigDatabase;
use App\Helpers\DBCleanup;
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
    public ?BaseRepository $repository;

    public ?Generator $faker;

    protected function setUp(): void
    {
        $this->repository = new BaseRepository();
        $this->faker = Factory::create();
        $this->brandId = ConfigDatabase::setUpBrands();
        $this->locationId = ConfigDatabase::setUpLocations();
        $this->modelId = ConfigDatabase::setUpCarModels();
    }

    protected function tearDown(): void
    {
        $this->repository = null;
        $this->faker = null;
        DBCleanup::cleanCarFeatures();
        DBCleanup::cleanVehicles();
        DBCleanup::cleanbrands();
        DBCleanup::cleanLocations();
        DBCleanup::cleanCarModels();
        $this->brandId = null;
        $this->locationId = null;
        $this->modelId = null;
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


    /**
     * @return void
     */
    public function testUpdate()
    {
        $repo = new BaseRepository();
        $res = $repo->setTable('car_brands')->save(
            [
                'id' => $this->brandId,
                'brand_name' => $this->faker->unique()->text(90)
            ]
        );
        $this->assertTrue($res);
        $this->assertEquals( 1, $repo->getCount());
    }

    /**
     * @depends testCreate
     * @return void
     */
    public function testDeelte()
    {
        $repo = new BaseRepository();
        $repo->setTable(VehicleRepository::TABLE_NAME)
            ->save(
                [
                    'location' => $this->locationId,
                    'car_brand' =>$this->brandId,
                    'car_model' => $this->modelId,
                    'license_plate' => $this->faker->optional()->text(10),
                ]
            );
        $vehicleId = $repo->lastSavedId();
        $res = $this->repository->setTable(VehicleRepository::TABLE_NAME)->delete([
                'id' => $vehicleId
        ]);
        $this->assertTrue($res);
    }
}
