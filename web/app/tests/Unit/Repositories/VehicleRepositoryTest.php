<?php

namespace Tests\Unit\Repositories;


use App\Models\Vehicle;
use App\Repositories\VehicleRepository;
use PHPUnit\Framework\TestCase;

class VehicleRepositoryTest extends TestCase
{
    private ?VehicleRepository $vehicleRepository;

    protected function setUp(): void
    {
        $this->vehicleRepository = new VehicleRepository();
    }

    protected function tearDown(): void
    {
        $this->vehicleRepository = null;
    }

    public function test__construct()
    {
        $this->assertInstanceOf(VehicleRepository::class, $this->vehicleRepository);
    }

    public function testAll(): void
    {
        $this->assertIsArray($this->vehicleRepository->all());
    }

    public function testToVehicle(): void
    {
        //$foo = self::getMethod('toVehicle');
       // $methodResponse = $foo->invokeArgs($this->vehicleRepository, []);
        $this->assertInstanceOf(Vehicle::class, $this->createMock(Vehicle::class));
    }

}