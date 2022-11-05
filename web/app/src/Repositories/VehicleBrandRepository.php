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
     * @param bool $returnFull
     * @return array|int|false
     */
    public function createBrand(string $brand_name, bool $returnFull = false): array|int|false
    {
        try {
            $input = ['brand_name' => $brand_name];
            if ($brandObject = $this->findOne($input)) {
                return $returnFull ? $brandObject : $brandObject[self::PK];
            }
            $brand = $this->create($input);
            if ($brand) {
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