<?php

namespace App\Helpers;

use App\Repositories\BaseRepository;
use App\Repositories\VehicleFeaturesRepository;
use App\Repositories\VehicleLocationRepository;
use App\Repositories\VehicleModelRepository;
use App\Repositories\VehicleRepository;

/**
 * Used only for testing application.
 */
class DBCleanup
{
    /**
     * @return void
     */
    public static function cleanVehicles()
    {
        (new VehicleRepository())->query("DELETE FROM vehicles");
    }

    /**
     * @return void
     */
    public static function cleanbrands()
    {
        (new BaseRepository())->query("DELETE FROM car_brands");
    }

    /**
     * @return void
     */
    public static function cleanLocations()
    {
        (new VehicleLocationRepository())->query("DELETE FROM car_locations");
    }

    /**
     * @return void
     */
    public static function cleanCarModels()
    {
        (new VehicleModelRepository())->query("DELETE FROM car_models");
    }

    /**
     * @return void
     */
    public static function cleanCarFeatures()
    {
        (new VehicleFeaturesRepository())->query("DELETE FROM car_features");
    }
}