<?php

namespace App\Oscar\Services;

use App\Oscar\Contracts\FileReaderInterface;
use JsonException;

class JsonFileReaderService implements FileReaderInterface
{
    /**
     * @var array
     */
    public array $data;

    /**
     * Read the whole json file and return as array of records.
     *
     * @param string $filePath full path of the file.
     * @return JsonFileReaderService list of records
     * @throws JsonException
     */
    public function read(string $filePath):static
    {
        // Read the JSON file
        $json = file_get_contents($filePath);

        // Decode the JSON file
        $this->data =  json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return $this;
    }

    public function toObject(): iterable
    {
        return [];
    }
}