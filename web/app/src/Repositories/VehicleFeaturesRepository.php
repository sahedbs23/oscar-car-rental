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
        $exists = $this->findOne(['vehicle_id' => $input['vehicle_id']]);

        if ($exists) {
            $input[self::PK] = $exists[self::PK];

            $this->update($input);

            return $exists[self::PK];
        }

        $this->create($input);

        return $this->lastSavedId();
    }


    /**
     * Find a vehicle feature details
     *
     * @param int $featureId
     * @return false|array
     */
    public function findCarFeatureById(int $featureId): false|array
    {
        return $this->findById($featureId);
    }

}