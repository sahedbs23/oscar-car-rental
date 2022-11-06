<?php

namespace Http\Controller;

use App\Exceptions\RecordNotFoundException;
use App\Exceptions\ValidationExceptions;
use App\Helpers\Arr;
use App\Helpers\ConfigDatabase;
use App\Helpers\DBCleanup;
use App\Http\Controller\CarController;
use App\Lib\Request;
use App\Lib\Response;
use App\Services\VehicleService;
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
        DBCleanup::cleanCarFeatures();
        DBCleanup::cleanVehicles();
        DBCleanup::cleanbrands();
        DBCleanup::cleanCarModels();
        DBCleanup::cleanLocations();
    }

    public function testSaveWithInvalidData()
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->any())
            ->method('getBody')
            ->willReturn(
                [
                    'location' => $this->faker->city(),
                    'car_brand' => $this->faker->randomLetter(),
                    'car_model' => $this->faker->randomLetter(),
                    'license_plate' => $this->faker->unique()->text(10),
                    'car_year' => $this->faker->optional()->numberBetween(1950, 2022),
                    'number_of_doors' => $this->faker->optional()->randomNumber(2),
                    'number_of_seats' => $this->faker->optional()->randomNumber(1),
                    'car_km' => $this->faker->optional()->numberBetween(0, 1000000),
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
                    'location' => $this->faker->city(),
                    'car_brand' => $this->faker->text(6),
                    'car_model' => $this->faker->text(6),
                    'license_plate' => $this->faker->unique()->text(10),
                    'car_year' => $this->faker->numberBetween(1950, 2022),
                    'number_of_doors' => $this->faker->randomNumber(2),
                    'number_of_seats' => $this->faker->randomNumber(1),
                    'car_km' => $this->faker->numberBetween(0, 1000000),
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

    /**
     * @return void
     * @throws RecordNotFoundException
     * @throws \JsonException
     */
    public function testView(): void
    {
        $vehicleId = ConfigDatabase::setupVehicle();
        $request = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);
        $this->assertNull($this->controller->view($request, $response, $vehicleId));
    }

    /**
     * @return void
     * @throws RecordNotFoundException
     */
    public function testViewException(): void
    {
        $request = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);
        $this->expectException(RecordNotFoundException::class);
        $this->controller->view($request, $response, -1);
    }

    public function testIndex()
    {
        $input = $this->rawUserInput();
        (new VehicleService())->storeCar($input);
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('getParams')
            ->willReturn(
                Arr::only($input, [
                    'car_model',
                    'car_brand',
                    'location',
                    'license_plate',
                    'fuel_type',
                    'transmission',
                    'car_type'
                ])
            );

        $response = $this->createMock(Response::class);
        $this->assertNull($this->controller->index($request, $response));
    }

    /**
     * @return array
     */
    private function rawUserInput(): array
    {
        return [
            'location' => $this->faker->realTextBetween(6, 10) . time(),
            'car_brand' => $this->faker->realTextBetween(6, 10) . time(),
            'car_model' => $this->faker->realTextBetween(6, 10) . time(),
            'license_plate' => $this->faker->unique()->text(6) . '-' . time(),
            'car_year' => $this->faker->year(),
            'number_of_doors' => $this->faker->randomDigit(),
            'number_of_seats' => $this->faker->randomDigit(),
            'fuel_type' => $this->faker->realTextBetween(6, 10),
            'transmission' => $this->faker->realTextBetween(6, 10),
            'car_type_group' => $this->faker->realTextBetween(6, 10),
            'car_type' => $this->faker->realTextBetween(6, 10),
            'car_km' => $this->faker->numberBetween(0, 20000)
        ];
    }
}
