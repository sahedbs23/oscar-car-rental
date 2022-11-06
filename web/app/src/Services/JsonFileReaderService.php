<?php

namespace App\Services;

use App\Contracts\FileReaderInterface;
use JsonException;

class JsonFileReaderService implements FileReaderInterface
{
    /**
     * @var array
     */
    public array $data;

    private array $fieldTemplate = [
        'location' => '',
        'car_brand' => '',
        'car_model' => '',
        'license_plate' => '',
        'car_year' => '',
        'number_of_doors' => '',
        'number_of_seats' => '',
        'fuel_type' => '',
        'transmission' => '',
        'car_type_group' => '',
        'car_type' => '',
        'car_km' => 0,
        'inside_height' => null,
        'inside_length' => null,
        'inside_width' => null
    ];

    /**
     * Read the whole json file and return as array of records.
     *
     * @param string $filePath full path of the file.
     * @return JsonFileReaderService list of records
     * @throws JsonException
     */
    public function read(string $filePath): static
    {
        // Read the JSON file
        $json = file_get_contents($filePath);

        // Decode the JSON file
        $this->data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return $this;
    }

    public function transform(): array
    {
        $lists = [];
        foreach ($this->data as $row):
            $lists[] = $this->toObject($row);
        endforeach;
        return $lists;
    }

    public function toObject(array $row): object
    {
        $obj = (object)$this->fieldTemplate;

        foreach ($row as $key => $value):
            $obj->{strtolower(str_replace(" ", "_", trim($key)))} = $value;
        endforeach;
        return $obj;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}