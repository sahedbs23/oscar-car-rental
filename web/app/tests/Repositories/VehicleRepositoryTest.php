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
        $vehicleId = $this->repository->createVehicle(ConfigDatabase::vehicleInput());
        $this->assertIsInt($vehicleId);
    }

    /**
     * @return void
     */
    public function testCreateFailed()
    {
        $input = ConfigDatabase::vehicleInput();
        $input['license_plate'] = 'Dhaka-KHA-512';
        $this->repository->createVehicle($input);
        $this->expectException(\PDOException::class);
        $this->repository->createVehicle($input);
    }

    /**
     * @return void
     */
    public function testFindVehicles()
    {
        $vehicleInput = ConfigDatabase::vehicleInput();
        $this->repository->createVehicle($vehicleInput);
        $this->repository->createVehicle(ConfigDatabase::vehicleInput());
        $response = $this->repository->findVehicles(Arr::only($vehicleInput, 'car_brand'));
        $this->assertIsArray($response);
    }
}
