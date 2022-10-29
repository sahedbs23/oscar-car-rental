<?php

namespace App\Oscar\Services;

use App\Oscar\Contracts\FileReaderInterface;

class CsvFileReaderService implements FileReaderInterface
{
    /**
     * @var array
     */
    private array $data;

    private array $fillableField = [
        'location',
        'car_brand',
        'car_model',
        'license_plate',
        'car_year',
        'number_of_doors',
        'number_of_seats',
        'fuel_type',
        'transmission',
        'car_type_group',
        'car_type',
        'car_km',
        'inside_height',
        'inside_length',
        'inside_width'
    ];

    /**
     * Read the whole csv file and return as array of records.
     *
     * @param string $filePath full path of the file.
     * @return CsvFileReaderService list of records
     */
    public function read(string $filePath):static
    {
        $records = [];
        if (($open = fopen($filePath, 'rb')) !== FALSE)
        {

            while (($data = fgetcsv($open, 1000, ",")) !== FALSE)
            {
                $records[] = $data;
            }

            fclose($open);
        }
        $this->data = $records;

        return $this;
    }

    public function toObject(): iterable
    {
        foreach ($this->data as $key => $value):
            var_dump($key, $value);
        endforeach;
        return [];
    }
}