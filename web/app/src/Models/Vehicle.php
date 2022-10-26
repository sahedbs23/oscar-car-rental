<?php
namespace App\Oscar\Models;

class Vehicle
{
    public string $location;
    public string $car_brand;
    public string $car_model;
    public string $license_plate;
    public int $car_year;
    public int $number_of_door;
    public int $number_of_seat;
    public string $fuel_type;
    public ?string $transmission;
    public ?string $car_group;
    public ?string $car_type;
    public ?int $car_km;
    public ?float $inside_height;
    public ?float $inside_length;
    public ?float $inside_width;

    /**
     * @param string $location
     * @param string $car_brand
     * @param string $car_model
     * @param string $license_plate
     * @param int $car_year
     * @param int $number_of_door
     * @param int $number_of_seat
     * @param string $fuel_type
     * @param string|null $transmission
     * @param string|null $car_group
     * @param string|null $car_type
     * @param int $car_km
     * @param float|null $inside_height
     * @param float|null $inside_length
     * @param float|null $inside_width
     */
    public function __construct(
        string $location,
        string $car_brand,
        string $car_model,
        string $license_plate,
        int    $car_year,
        int    $number_of_door,
        int    $number_of_seat,
        string $fuel_type,
        string $transmission = null,
        string $car_group = null,
        string $car_type = null,
        int    $car_km = 0,
        float  $inside_height = null,
        float  $inside_length = null,
        float  $inside_width = null
    )
    {
        $this->location = $location;
        $this->car_brand = $car_brand;
        $this->car_model = $car_model;
        $this->license_plate = $license_plate;
        $this->car_year = $car_year;
        $this->number_of_door = $number_of_door;
        $this->number_of_seat = $number_of_seat;
        $this->fuel_type = $fuel_type;
        $this->transmission = $transmission;
        $this->car_group = $car_group;
        $this->car_type = $car_type;
        $this->car_km = $car_km;
        $this->inside_height = $inside_height;
        $this->inside_length = $inside_length;
        $this->inside_width = $inside_width;
    }
}