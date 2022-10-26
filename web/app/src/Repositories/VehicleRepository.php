<?php

use App\Oscar\Models\Vehicle;

class VehicleRepository
{
    /**
     * @var DatabaseConnection
     */
    private DatabaseConnection $connection;

    public function __construct()
    {
        $this->connection = new DatabaseConnection;
    }

    /**
     * @return Vehicle[]
     */
    public function all(): array
    {
        $users = $this->connection->connection
            ->query('SELECT * FROM vehicles');
        $collection = [];
        foreach ($users as $user):
            $collection[] = $this->toVehicle($user);
        endforeach;
        return $collection;

    }

    private function toVehicle(array $record): Vehicle
    {
        return new Vehicle();
    }
}