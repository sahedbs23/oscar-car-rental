<?php

namespace App\Repositories;

class VehicleLocationRepository extends BaseRepository
{
    public const TABLE_NAME = 'car_locations';

    public const PK = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->setPrimaryKey(self::PK);
        $this->setTable(self::TABLE_NAME);
    }

    /**
     * @param string $locationName
     * @return int|false
     */
    public function createCarLocation(string $locationName): int|false
    {
        try {
            $input = ['location' => $locationName];
            if ($exists = $this->findOne($input)) {
                return $exists[self::PK];
            }
            $this->create($input);
            return $this->lastSavedId();
        } catch (\Exception $exception) {
            // Do Nothing.
        }
        return false;
    }


    /**
     * @param string $locationName
     * @return array|false
     */
    public function updateOrCreate(string $locationName): array|false
    {
        try {
            $input = ['location' => $locationName];
            if ($exists = $this->findOne($input)) {
                return $exists;
            }
            $this->create($input);
            return $this->findById($this->lastSavedId());
        } catch (\Exception $exception) {
            // Do Nothing.
        }
        return false;
    }
}