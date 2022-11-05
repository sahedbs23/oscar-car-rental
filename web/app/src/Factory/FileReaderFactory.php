<?php

namespace App\Factory;

use App\Contracts\FileReaderInterface;
use App\Services\CsvFileReaderService;
use App\Services\JsonFileReaderService;
use RuntimeException;

class FileReaderFactory
{
    /**
     * @param string $fileExtension
     * @return FileReaderInterface
     */
    public function create(string $fileExtension): FileReaderInterface
    {
        return match (strtolower($fileExtension)) {
            'csv' => new CsvFileReaderService(),
            'json' => new JsonFileReaderService(),
            default => throw new RuntimeException('Invalid file type'),
        };
    }
}