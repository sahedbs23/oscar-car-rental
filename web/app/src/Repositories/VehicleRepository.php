<?php

namespace App\Repositories;

use App\Lib\DB\DatabaseConnection;
use App\Models\Vehicle;

class VehicleRepository
{
    /**
     * @var \PDO
     */
    private ?\PDO $connection;

    public function __construct()
    {
        $this->connection =  DatabaseConnection::databaseManager();
    }

    /**
     * @return Vehicle[]
     */
    public function all(): array
    {
        $vehicles = $this->connection
            ->query('SELECT * FROM vehicles');
        $collection = [];
        foreach ($vehicles as $vehicle):
            $collection[] = $this->toVehicle($vehicle);
        endforeach;
        return $collection;

    }

    private function toVehicle(array $record): Vehicle
    {
        [
            $location,
            $car_brand,
            $car_model,
            $license_plate,
            $car_year,
            $number_of_door,
            $number_of_seat,
            $fuel_type,
            $transmission,
            $car_group,
            $car_type,
            $car_km,
            $inside_height,
            $inside_length,
            $inside_width
        ] = $record;
        return new Vehicle(
            $location,
            $car_brand,
            $car_model,
            $license_plate,
            $car_year,
            $number_of_door,
            $number_of_seat,
            $fuel_type,
            $transmission,
            $car_group,
            $car_type,
            $car_km,
            $inside_height,
            $inside_length,
            $inside_width
        );
    }
}