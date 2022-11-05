<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Repositories\VehicleBrandRepository;
use App\Repositories\VehicleLocationRepository;
use App\Repositories\VehicleModelRepository;
use App\Repositories\VehicleRepository;

class vehicleService
{
    /**
     * @var VehicleRepository
     */
    private VehicleRepository $repository;
    /**
     * @var VehicleBrandRepository
     */
    private VehicleBrandRepository $brandRepository;
    /**
     * @var VehicleLocationRepository
     */
    private VehicleLocationRepository $locationRepository;
    /**
     * @var VehicleModelRepository
     */
    private VehicleModelRepository $modelRepository;

    public function __construct()
    {
        $this->repository = new VehicleRepository();
        $this->brandRepository = new VehicleBrandRepository();
        $this->modelRepository = new VehicleModelRepository();
        $this->locationRepository = new VehicleLocationRepository();
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
        return $this->repository->findVehicles($params);
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