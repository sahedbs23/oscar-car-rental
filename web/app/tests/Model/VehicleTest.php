<?php

namespace Model;

use App\Models\Vehicle;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class VehicleTest extends TestCase
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function test__construct(): void
    {
        $vehicle = new Vehicle(
            $this->faker->city(),
            $this->faker->randomLetter(),
            $this->faker->randomLetter(),
            $this->faker->unique()->randomDigit(),
            $this->faker->numberBetween(1950, 2022),
            $this->faker->randomNumber(2),
            $this->faker->randomNumber(1),
            $this->faker->text(5),
            $this->faker->optional()->text(6),
            $this->faker->optional()->text(5),
            $this->faker->optional()->text(8),
            $this->faker->numberBetween(0, 1000000),
            $this->faker->optional()->randomFloat(),
            $this->faker->optional()->randomFloat(),
            $this->faker->optional()->randomFloat()
        );
        $this->assertInstanceOf(Vehicle::class, $vehicle);
    }
}
