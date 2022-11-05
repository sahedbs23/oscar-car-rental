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
     * @param bool $returnFull
     * @return array|int|false
     */
    public function createCarLocation(string $locationName, bool $returnFull = false): array|int|false
    {
        try {
            $input = ['location' => $locationName];
            if ($locationObject = $this->findOne($input)) {
                return $returnFull ? $locationObject : $locationObject[self::PK];
            }
            $location = $this->create($input);
            if ($location) {
                if (!$returnFull) {
                    return $this->lastSavedId();
                }
                return $this->findById($this->lastSavedId());
            }
        } catch (\Exception $exception) {
            // Do Nothing.
        }
        return false;
    }
}