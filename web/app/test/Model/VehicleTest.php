<?php

namespace AppTest\Oscar;

use App\Oscar\Models\Vehicle;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class VehicleTest extends TestCase
{
    private Generator $faker;

    public function setUp():void
    {
        $this->faker = Factory::create();
    }

    public function test__construct(): void
    {
        $vehicle =  new Vehicle(
            $this->faker->text(10),
            $this->faker->text(10),
            $this->faker->text(10),
            $this->faker->text(10),
            $this->faker->numberBetween(1950, 2022),
            $this->faker->numberBetween(1, 5),
            $this->faker->numberBetween(1, 20),
            $this->faker->text(5),
        );
        var_dump(gettype($vehicle));
        $this->assertEquals($vehicle, Vehicle::class);
    }
}
