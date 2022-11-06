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
     * @param string $brandName
     * @return int|false
     */
    public function createBrand(string $brandName): int|false
    {
        try {
            $input = ['brand_name' => $brandName];

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
     * @param int $brandId
     * @return array|false
     */
    public function findBranById(int $brandId): array|false
    {
        return $this->findById($brandId);
    }
}