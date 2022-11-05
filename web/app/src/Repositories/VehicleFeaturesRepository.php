<?php

namespace App\Repositories;

class VehicleFeaturesRepository extends BaseRepository
{
    public const TABLE_NAME = 'car_features';

    public const PK = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->setPrimaryKey(self::PK);
        $this->setTable(self::TABLE_NAME);
    }

    /**
     * save vehicle features.
     *
     * @param array $input
     * @param bool $returnFull
     * @return false|int|array
     */
    public function saveCarFeatures(array $input, bool $returnFull = false): false|int|array
    {
        if ($exists = $this->findOne(['vehicle_id' => $input['vehicle_id']])) {
            $lastId = $exists[self::PK];
            $input[self::PK] = $exists[self::PK];
            $feature = $this->update($input);
        } else {
            $feature = $this->create($input);
            $lastId = $this->lastSavedId();
        }

        if ($feature && $returnFull) {
            return $this->findById($lastId);
        }

        if ($feature) {
            return $lastId;
        }
        return false;
    }

}