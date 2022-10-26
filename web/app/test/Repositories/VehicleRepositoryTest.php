<?php

namespace AppTest\Oscar\Repositories;


use App\Oscar\Models\Vehicle;
use App\Oscar\Repositories\VehicleRepository;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

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

    protected static function getMethod($name)
    {
        $class = new ReflectionClass(VehicleRepository::class);
        $method = $class->getMethod('toVehicle');
        $method->setAccessible(true);
        return $method;
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
        $foo = self::getMethod('toVehicle');
        $methodResponse = $foo->invokeArgs($this->vehicleRepository, []);
        $this->assertInstanceOf(Vehicle::class, $methodResponse);
    }

}
