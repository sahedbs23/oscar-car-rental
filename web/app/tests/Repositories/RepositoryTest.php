<?php

namespace Repositories;

use App\Repositories\BaseRepository;
use App\Repositories\VehicleRepository;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    public function test__construct()
    {
        $repository = new VehicleRepository();
        $this->assertEquals(VehicleRepository::TABLE_NAME, $repository->getTable());
        $this->assertEquals(VehicleRepository::PK, $repository->getPk());
    }
}
