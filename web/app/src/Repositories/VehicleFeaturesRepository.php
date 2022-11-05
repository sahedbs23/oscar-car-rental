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
}