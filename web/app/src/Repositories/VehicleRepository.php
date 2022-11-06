<?php

namespace App\Repositories;

use PDOException;

class VehicleRepository extends BaseRepository
{
    public const TABLE_NAME = 'vehicles';

    public const PK = 'id';

    public const FIELDS = [
        'v.id',
        'l.location as location',
        'cb.brand_name as car_brand',
        'cm.car_model as car_model',
        'v.license_plate',
        'v.car_year',
        'v.number_of_doors',
        'v.number_of_seats',
        'v.fuel_type',
        'v.transmission',
        'v.car_type_group',
        'v.car_type',
        'v.car_km',
        'cf.inside_height',
        'cf.inside_length',
        'cf.inside_width'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->setPrimaryKey(self::PK);
        $this->setTable(self::TABLE_NAME);
    }

    /**
     * @param int $vehicleId
     * @return array|false
     */
    public function findVehicle(int $vehicleId): array|false
    {
        $vehicle = $this->findById($vehicleId);
        if (is_array($vehicle) && array_key_exists('car_brand', $vehicle)) {
            $vehicle['car_brand'] = (new VehicleBrandRepository())->findById($vehicle['car_brand']);
        }

        if (is_array($vehicle) && array_key_exists('car_model', $vehicle)) {
            $vehicle['car_model'] = (new VehicleModelRepository())->findById($vehicle['car_model']);
        }

        if (is_array($vehicle) && array_key_exists('location', $vehicle)) {
            $vehicle['location'] = (new VehicleLocationRepository())->findById($vehicle['location']);
        }

        if (!empty($vehicle)) {
            $features = (new VehicleFeaturesRepository())->findOne(['vehicle_id' => $vehicle['id']]);
            $vehicle['features'] = empty($features) ? [] : $features;
        }

        return $vehicle;
    }

    /**
     * @param array $search
     * @param int $limit
     * @param int $offset
     * @return array|false
     */
    public function findVehicles(array $search = [], int $limit = 10, int $offset = 0): array|false
    {
        $searchCriteria = [];
        $searchCriteria['fields'] = self::FIELDS;
        $searchCriteria['limit'] = $limit;
        $searchCriteria['offset'] = $offset;

        if (is_array($search) && !empty($search)) {
            if (array_key_exists('car_brand', $search)) {
                $search['cb.brand_name'] = $search['car_brand'];
                unset($search['car_brand']);
            }

            if (array_key_exists('location', $search)) {
                $search['l.location'] = $search['location'];
                unset($search['location']);
            }

            if (array_key_exists('car_model', $search)) {
                $search['cm.car_model'] = $search['car_model'];
                unset($search['car_model']);
            }
            $searchCriteria['conditions'] = $search;
        }

        return $this->setTable(
            'vehicles as v 
        left join car_brands as cb on v.car_brand = cb.id
        left join car_models as cm on v.car_model = cm.id
        left join car_locations as l on v.location = l.id
        left join car_features as cf on cf.vehicle_id = v.id'
        )->findAll($searchCriteria);
    }

    /**
     * create a new Vehicle.
     *
     * @param array $input
     * @return int
     * @throws PDOException
     */
    public function createVehicle(array $input): int
    {
        $this->create($input);
        return $this->lastSavedId();
    }

}