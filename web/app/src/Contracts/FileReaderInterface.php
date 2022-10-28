<?php

namespace App\Oscar\Contracts;

interface FileReaderInterface
{
    /**
     * Read the whole json file and return as array of records.
     *
     * @param string $filePath full path of the file.
     * @return array list of records
     */
    public function read(string $filePath): array;
}