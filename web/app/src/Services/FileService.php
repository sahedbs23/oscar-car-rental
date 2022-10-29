<?php

namespace App\Oscar\Services;

use App\Oscar\Contracts\FileReaderInterface;
use App\Oscar\Factory\FileReaderFactory;

class FileService
{
    private FileReaderFactory $fileFactory;

    public function __construct()
    {
        $this->fileFactory = new FileReaderFactory();
    }

    /**
     * Read the file content.
     *
     * @param string $fileType
     * @param string $filePath
     * @return iterable
     */
    public function readFileContent(string $fileType, string $filePath): iterable
    {
        return $this->fileFactory
            ->create($fileType)
            ->read($filePath)
            ->toObject();
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
            __DIR__.'/../../data_source/source-1.csv',
            __DIR__.'/../../data_source/source-2.json',
            __DIR__.'/../../data_source/source-3.json',
        ];
    }


    /**
     * get the file extension from file path.
     * @param string $filename
     * @return string
     */
    public function findFileExtention(string $filename): string
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