<?php

namespace App\Helpers;

use App\Repositories\VehicleBrandRepository;
use App\Repositories\VehicleLocationRepository;
use App\Repositories\VehicleModelRepository;
use App\Repositories\VehicleRepository;
use Faker\Factory;
use Faker\Generator;

/**
 * Use only for testing application.
 */
class ConfigDatabase
{
    /**
     * @var Generator
     */
    public static Generator $instance;

    /**
     * @return void
     */
    public static function instance()
    {
        self::$instance = Factory::create();
    }

    public static function insantiate()
    {
        if (!isset(self::$instance)) {
            self::instance();
        }
    }

    /**
     * @return int
     */
    public static function setUpBrands(): int
    {
        self::insantiate();
        return (new VehicleBrandRepository())->createBrand(self::$instance->unique()->text(6) . '-' . time());
    }

    /**
     * @return int
     */
    public static function setUpLocations(): int
    {
        self::insantiate();
        return (new VehicleLocationRepository())->createCarLocation(self::$instance->unique()->text(6) . '-' . time());
    }

    /**
     * @return int
     */
    public static function setUpCarModels(): int
    {
        self::insantiate();
        return (new VehicleModelRepository())->createModel(self::$instance->unique()->text(6) . '-' . time());
    }

    /**
     * @return int|false
     */
    public static function setupVehicle(): int|false
    {
        self::insantiate();
        return (new VehicleRepository())->createVehicle(self::vehicleInput());
    }

    /**
     * @return array
     */
    public static function vehicleInput(): array
    {
        self::insantiate();
        return [
            'location' => self::setUpLocations(),
            'car_brand' => self::setUpBrands(),
            'car_model' => self::setUpCarModels(),
            'license_plate' => self::$instance->unique()->text(6) . '-' . time(),
            'car_year' => self::$instance->optional()->year(),
            'number_of_door' => self::$instance->optional()->randomDigit(),
            'number_of_seat' => self::$instance->optional()->randomDigit(),
            'fuel_type' => self::$instance->optional()->realTextBetween(6, 10),
            'transmission' => self::$instance->optional()->realTextBetween(6, 10),
            'car_group' => self::$instance->optional()->realTextBetween(6, 10),
            'car_type' => self::$instance->optional()->realTextBetween(6, 10),
            'car_km' => self::$instance->optional()->numberBetween(0, 20000),
        ];
    }
}