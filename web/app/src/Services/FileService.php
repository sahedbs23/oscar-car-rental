<?php

namespace App\Oscar\Services;

use App\Oscar\Factory\FileReaderFactory;

class FileService
{
    private FileReaderFactory $fileFactory;

    public function __construct()
    {
        $this->fileFactory = new FileReaderFactory();
    }

    public function processFile()
    {
        $this->fileFactory
            ->create('csv')
            ->read('');
    }


    /**
     * get the file extension from file path.
     * @param string $filename
     * @return string
     */
    public function findFileExtention(string $filename)
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