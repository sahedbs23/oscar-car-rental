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
     * @return false|int
     */
    public function saveCarFeatures(array $input): false|int
    {
        if ($exists = $this->findOne(['vehicle_id' => $input['vehicle_id']])) {
            $input[self::PK] = $exists[self::PK];
            $this->update($input);
            return $exists[self::PK];
        }

        $this->create($input);
        return $this->lastSavedId();
    }


    /**
     * save vehicle features.
     *
     * @param array $input
     * @return false|array
     */
    public function findOrCarFeatures(array $input): false|array
    {
        if ($exists = $this->findOne(['vehicle_id' => $input['vehicle_id']])) {
            $input[self::PK] = $exists[self::PK];
            $feature = $this->update($input);
            return $this->findById($exists[self::PK]);
        }

        $this->create($input);
        $lastId = $this->lastSavedId();

        return $this->findById($lastId);
    }

}