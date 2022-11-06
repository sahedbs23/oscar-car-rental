<?php

namespace App\Services;

use App\Contracts\FileReaderInterface;

class CsvFileReaderService implements FileReaderInterface
{
    /**
     * @var array
     */
    private array $data;

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
     * Read the whole csv file and return as array of records.
     *
     * @param string $filePath full path of the file.
     * @return CsvFileReaderService list of records
     */
    public function read(string $filePath): static
    {
        $records = [];
        if (($open = fopen($filePath, 'rb')) !== false) {
            while (($data = fgetcsv($open, 1000, ",")) !== false) {
                $records[] = $data;
            }

            fclose($open);
        }
        $this->data = $records;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function transform(): array
    {
        $output = [];
        foreach ($this->data as $index => $value):
            if ($index === 0) {
                continue;
            }
            $output[] = (object)$this->transformToArray($value);
        endforeach;
        return $output ;
    }

    /**
     * transform a csv row to template array.
     * @param array $row
     * @return array
     */
    public function transformToArray(array $row): array
    {
        $tempIndex = 0;
        $rowArray = [];
        foreach ($this->fieldTemplate as $fieldKey => $fieldValue) :
            $fieldValue = array_key_exists($tempIndex, $row) ? trim($row[$tempIndex]) : $fieldValue;
            $rowArray[$fieldKey] = $fieldValue;
            $tempIndex++;
        endforeach;

        return $rowArray;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

}