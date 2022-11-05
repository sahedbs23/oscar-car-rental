<?php

namespace Http\Controller;

use App\Exceptions\ValidationExceptions;
use App\Http\Controller\CarController;
use App\Lib\Request;
use App\Lib\Response;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class CarControllerTest extends TestCase
{
    public ?Generator $faker;

    public ?CarController $controller;

    public function setUp(): void
    {
       $this->faker = Factory::create();
       $this->controller = new CarController();
    }

    protected function tearDown(): void
    {
        $this->faker = null;
        $this->controller = null;
    }

    public function testSaveWithInvalidData()
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->any())
            ->method('getBody')
            ->willReturn(
                [
                    'location' =>   $this->faker->city(),
                    'car_brand' => $this->faker->randomLetter(),
                    'car_model' => $this->faker->randomLetter(),
                    'license_plate' => $this->faker->unique()->text(10),
                    'car_year' => $this->faker->optional()->numberBetween(1950, 2022),
                    'number_of_doors' => $this->faker->optional()->randomNumber(2),
                    'number_of_seats' => $this->faker->optional()->randomNumber(1),
                    'car_km' => $this->faker->optional()->numberBetween(0,1000000),
                    'fuel_type' => $this->faker->optional()->text(6),
                    'transmission' => $this->faker->optional()->text(6),
                    'car_type_group' => $this->faker->optional()->text(6),
                    'car_type' => $this->faker->optional()->text(6),
                    'inside_height' => $this->faker->optional()->randomFloat(),
                    'inside_length' => $this->faker->optional()->randomFloat(),
                    'inside_width' => $this->faker->optional()->randomFloat(),
                ]
            );

        $response = $this->createMock(Response::class);
        $this->expectException(ValidationExceptions::class);
        $this->controller->save($request, $response);
    }

    public function testSaveWithValidData()
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->any())
            ->method('getBody')
            ->willReturn(
                [
                    'location' =>   $this->faker->city(),
                    'car_brand' => $this->faker->text(6),
                    'car_model' =>$this->faker->text(6),
                    'license_plate' => $this->faker->unique()->text(10),
                    'car_year' => $this->faker->numberBetween(1950, 2022),
                    'number_of_doors' => $this->faker->randomNumber(2),
                    'number_of_seats' => $this->faker->randomNumber(1),
                    'car_km' => $this->faker->numberBetween(0,1000000),
                    'fuel_type' => $this->faker->text(6),
                    'transmission' => $this->faker->text(6),
                    'car_type_group' => $this->faker->text(6),
                    'car_type' => $this->faker->text(6),
                    'inside_height' => $this->faker->randomFloat(),
                    'inside_length' => $this->faker->randomFloat(),
                    'inside_width' => $this->faker->optional()->randomFloat(),
                ]
            );

        $response = $this->createMock(Response::class);
        $response->expects($this->any())
            ->method('send')
            ->with(true);

       $this->assertNull($this->controller->save($request, $response));
    }
}
