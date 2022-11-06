<?php

namespace App\Services;

use App\Exceptions\RecordNotFoundException;
use App\Helpers\Arr;
use App\Repositories\VehicleBrandRepository;
use App\Repositories\VehicleFeaturesRepository;
use App\Repositories\VehicleLocationRepository;
use App\Repositories\VehicleModelRepository;
use App\Repositories\VehicleRepository;

class VehicleService
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
    /**
     * @var VehicleFeaturesRepository
     */
    private VehicleFeaturesRepository $featuresRepository;

    public function __construct()
    {
        $this->repository = new VehicleRepository();
        $this->brandRepository = new VehicleBrandRepository();
        $this->modelRepository = new VehicleModelRepository();
        $this->locationRepository = new VehicleLocationRepository();
        $this->featuresRepository = new VehicleFeaturesRepository();
    }

    /**
     * @param array $input
     * @return false|int
     */
    public function storeCar(array $input)
    {
        $modelId = $this->modelRepository->createModel($input['car_model']);
        $brandId = $this->brandRepository->createBrand($input['car_brand']);
        $locationId = $this->locationRepository->createCarLocation($input['location']);

        $input['car_model'] = $modelId;
        $input['car_brand'] = $brandId;
        $input['location'] = $locationId;

        $this->repository->create(
            Arr::only(
                $input,
                [
                    'car_model',
                    'car_brand',
                    'location',
                    'license_plate',
                    'car_year',
                    'number_of_doors',
                    'number_of_seats',
                    'fuel_type',
                    'transmission',
                    'car_type_group',
                    'car_type',
                    'car_km',
                ]
            )
        );

        $vehicleId = $this->repository->lastSavedId();

        $this->saveCarFeatures($input, $vehicleId);

        return $vehicleId;
    }

    /**
     * @param array $params
     * @return array|null
     */
    public function searchCar(array $params = []): ?array
    {
        $conditions = Arr::only($params, [
                'car_model',
                'car_brand',
                'location',
                'license_plate',
                'fuel_type',
                'transmission',
                'car_type'
            ]
        );
        if (array_key_exists('limit', $params)) {
            $limit = $params['limit'];
            unset($params['limit']);
        }

        if (array_key_exists('offset', $params)) {
            $offset = $params['offset'];
            unset($params['offset']);
        }

        return $this->repository->findVehicles($conditions, $limit ?? 10, $offset ?? 0);
    }

    /**
     * @param int $id
     * @return array|false
     * @throws RecordNotFoundException
     */
    public function find(int $id): array|false
    {
        $response = $this->repository->findVehicle($id);

        if (!$response) {
            throw new RecordNotFoundException(sprintf('Record not found in the database with id %d.', $id));
        }
        return $response;
    }

    /**
     * @param $license
     * @return array|false
     */
    public function checkDuplicateLicense($license)
    {
        return $this->repository->findOne(['license_plate' =>$license]);
    }

    /**
     * @param array $input
     * @param int $vehicleId
     * @return bool
     * @throws \PDOException
     */
    public function saveCarFeatures(array $input, int $vehicleId): bool
    {
        if (
            !empty($input['inside_height']) ||
            !empty($input['inside_length']) ||
            !empty($input['inside_width'])
        ) {
            $inputs = Arr::only($input, ['inside_height', 'inside_length', 'inside_width']);
            $inputs = array_filter($inputs, static function ($item) {
                return !empty($item);
            });

            return (bool)$this->featuresRepository->saveCarFeatures(array_merge(['vehicle_id' => $vehicleId], $inputs));
        }
        return false;
    }
}