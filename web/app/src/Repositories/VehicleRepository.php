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
    public function findVehicle(int $vehicleId): mixed
    {
        return $this->findById($vehicleId);
    }

    /**
     * @param array $search
     * @param int $limit
     * @param int $offset
     * @return array|false
     */
    public function findVehicles(array $search = [], int $limit = 10, int $offset = 0): array|false
    {
        $searchCriteria['limit'] = $limit;
        $searchCriteria['offset'] = $offset;
        if (is_array($search) && !empty($search)) {
            $searchCriteria['conditions'] = $search;
        }
        return $this->findAll($searchCriteria);
    }

    /**
     * create a new Vehicle.
     *
     * @param array $input
     * @param bool $returnFull
     * @return int|array|false
     */
    public function createVehicle(array $input, bool $returnFull = false): int|array|false
    {
        $status = $this->create($input);
        if ($returnFull && $status) {
            return $this->findById($this->lastSavedId());
        }

        if ($status) {
            return $this->lastSavedId();
        }
        return false;
    }

}