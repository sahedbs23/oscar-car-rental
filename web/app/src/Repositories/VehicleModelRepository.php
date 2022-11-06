<?php

namespace App\Repositories;

class VehicleModelRepository extends BaseRepository
{
    public const TABLE_NAME = 'car_models';

    public const PK = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->setPrimaryKey(self::PK);
        $this->setTable(self::TABLE_NAME);
    }

    /**
     * @param string $carModel
     * @return int|false
     */
    public function createModel(string $carModel): int|false
    {
        try {
            $input = ['car_model' => $carModel];

            $exists = $this->findOne($input);

            if ($exists) {
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
     * @param int $modelId
     * @return array|false
     */
    public function findCarModelById(int $modelId): array|false
    {
        return $this->findById($modelId);
    }
}