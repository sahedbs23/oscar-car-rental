<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Vehicle;

class VehicleRepository extends BaseRepository
{
    public const TABLE_NAME = 'vehicles';

    public const PK = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->setPrimaryKey(self::PK);
        $this->setTable(self::TABLE_NAME);
    }

    /**
     * @return null|Vehicle
     */
//    public function findOne() :?Vehicle
//    {
//        return null;
//    }

    /**
     * @return Vehicle[]
     */
//    public function findMany() : array
//    {
//        $vehicles = $this->connection
//            ->query('SELECT * FROM vehicles');
//        $collection = [];
//        foreach ($vehicles as $vehicle):
//            $collection[] = $this->toVehicle($vehicle);
//        endforeach;
//        return $collection;
//    }

    /**
     * @param array $input
     * @return bool
     */
//    public function create(array $input):bool
//    {
//        return true;
//
//    }

    /**
     * @param array $record
     * @return Vehicle
     */
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