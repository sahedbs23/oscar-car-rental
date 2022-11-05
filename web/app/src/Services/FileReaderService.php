<?php

namespace App\Services;

use App\Factory\FileReaderFactory;

class FileReaderService
{
    public FileReaderFactory $fileFactory;

    public function __construct()
    {
        $this->fileFactory = new FileReaderFactory();
    }

    /**
     * Read the file content.
     *
     * @param string $fileType
     * @param string $filePath
     * @return array
     */
    public function readFileContent(string $fileType, string $filePath): array
    {
        return $this->fileFactory
            ->create($fileType)
            ->read($filePath)
            ->transform();
    }

    /**
     * scan the provided directory and list all files.
     *
     * @param string $directory
     * @return string[]
     */
    public function listFiles(string $directory): iterable
    {
        return [
            __DIR__ . '/../../data_source/source-1.csv',
            __DIR__ . '/../../data_source/source-2.json',
            __DIR__ . '/../../data_source/source-3.json',
        ];
    }


    /**
     * get the file extension from file path.
     * @param string $filename
     * @return string
     */
    public function findFileExtension(string $filename): string
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    /**
     * check whether a file is readable or not.
     *
     * @param string $filename
     * @return bool
     */
    public function fileReadable(string $filename): bool
    {
        return is_readable($filename);
    }
}