<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

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
     * @param int $vehicleId
     * @return mixed
     */
    public function findVehicle(int $vehicleId) :mixed
    {
        return $this->findById($vehicleId);
    }

    /**
     * @param array $search
     * @param int $limit
     * @param int $offset
     * @return array|false
     */
    public function findVehicles(array $search = [], int $limit = 10, int $offset=0) : array|false
    {
        $searchCriteria['limit']  = $limit;
        $searchCriteria['offset']  = $offset;
        if (is_array($search)  && !empty($search)){
            $searchCriteria['conditions'] = $search;
        }
        return $this->findAll($searchCriteria);
    }

    /**
     * @param array $input
     * @return mixed
     */
    public function createVehicle(array $input):mixed
    {
        $status =  $this->create($input);
        if ($status){
            return $this->findById($this->lastSavedId());
        }
        return null;
    }

}