<?php

namespace Repositories;

use App\Repositories\VehicleRepository;
use PHPUnit\Framework\TestCase;

class VehicleRepositoryTest extends TestCase
{
    public ?VehicleRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new VehicleRepository();
    }

    protected function tearDown(): void
    {
        $this->repository = null;
    }

//    public function testCreate()
//    {
//        $this->repository->createVehicle(
//
//        );
//    }
}
