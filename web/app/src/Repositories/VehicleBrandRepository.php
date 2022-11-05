<?php

namespace App\Repositories;

class VehicleBrandRepository extends BaseRepository
{
    public const TABLE_NAME = 'car_brands';

    public const PK = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->setPrimaryKey(self::PK);
        $this->setTable(self::TABLE_NAME);
    }

    /**
     * @param string $brand_name
     * @return int|false
     */
    public function createBrand(string $brand_name): int|false
    {
        try {
            $input = ['brand_name' => $brand_name];
            if ($brandObject = $this->findOne($input)) {
                return $brandObject[self::PK];
            }
            $brand = $this->create($input);
            if ($brand) {
                return $this->lastSavedId();
            }
        } catch (\Exception $exception) {
            // Do Nothing.
        }
        return false;
    }


    /**
     * @param string $brand_name
     * @return array|false
     */
    public function findOrCreateBrand(string $brand_name): array|false
    {
        try {
            $input = ['brand_name' => $brand_name];
            if ($brandObject = $this->findOne($input)) {
                return $brandObject;
            }
            $brand = $this->create($input);
            if ($brand) {
                return $this->findById($this->lastSavedId());
            }
        } catch (\Exception $exception) {
            // Do Nothing.
        }
        return false;
    }
}