<?php

namespace App\Oscar\Factory;

use App\Oscar\Contracts\FileReaderInterface;
use App\Oscar\Services\CsvFileReaderService;
use App\Oscar\Services\JsonFileReaderService;
use RuntimeException;

class FileReaderFactory
{
    /**
     * @param string $fileExtension
     * @return FileReaderInterface
     */
    public function create(string $fileExtension):FileReaderInterface
    {
        return match (strtolower($fileExtension)) {
            'csv' => new CsvFileReaderService(),
            'json' => new JsonFileReaderService(),
            default => throw new RuntimeException('Invalid file type'),
        };
    }
}