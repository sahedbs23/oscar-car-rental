<?php

namespace Repositories;

use App\Helpers\Arr;
use App\Helpers\ConfigDatabase;
use App\Helpers\DBCleanup;
use App\Repositories\VehicleRepository;
use PHPUnit\Framework\TestCase;

class VehicleRepositoryTest extends TestCase
{
    /**
     * @var VehicleRepository|null
     */
    public ?VehicleRepository $repository;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->repository = new VehicleRepository();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->repository = null;
        DBCleanup::cleanVehicles();
        DBCleanup::cleanbrands();
        DBCleanup::cleanLocations();
        DBCleanup::cleanCarModels();
    }

    /**
     * @return void
     */
    public function testCreateSuccesfull()
    {
        $vehicleId = $this->repository->createVehicle(ConfigDatabase::vehicleInput(), true);
        $this->assertIsArray($vehicleId);
    }

    /**
     * @return void
     */
    public function testCreateFailed()
    {
        $input = ConfigDatabase::vehicleInput();
        $input['license_plate'] = 'Dhaka-KHA-512';
        $vehicleId = $this->repository->createVehicle($input, true);
        $this->assertIsArray($vehicleId);
        $this->expectException(\PDOException::class);
        $this->repository->createVehicle($input);
    }

    /**
     * @return void
     */
    public function testFindVehicles()
    {
        $input1 = ConfigDatabase::vehicleInput();
        $this->repository->createVehicle($input1);
        $this->repository->createVehicle(ConfigDatabase::vehicleInput());
        $response = $this->repository->findVehicles(Arr::only($input1, 'car_brand'));
        $this->assertIsArray($response);
    }
}
