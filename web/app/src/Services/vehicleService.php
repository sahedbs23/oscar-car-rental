<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Repositories\VehicleRepository;

class vehicleService
{
    /**
     * @var VehicleRepository
     */
    private VehicleRepository $repository;

    public function __construct()
    {
        $repository = new VehicleRepository();
    }

    /**
     * @param array $input
     * @return bool
     */
    public function storeCar(array $input) :bool
    {
        return $this->repository->create($input);
    }

    /**
     * @param array $params
     * @return Vehicle[]|null
     */
    public function searchCar(array $params = []) :?array
    {
        return $this->repository->findMany($params);
    }

    /**
     * @param int $id
     * @return Vehicle|null
     */
    public function find(int $id) :?Vehicle
    {
        return $this->repository->findOne($id);
    }
}