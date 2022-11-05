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
     * @param string $car_model
     * @param bool $returnFull
     * @return array|int|false
     */
    public function createModel(string $car_model, bool $returnFull = false): array|int|false
    {
        try {
            $input = ['car_model' => $car_model];
            if ($modelObject = $this->findOne($input)) {
                return $returnFull ? $modelObject : $modelObject[self::PK];
            }
            $model = $this->create($input);
            if ($model) {
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