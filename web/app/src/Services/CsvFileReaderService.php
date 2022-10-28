<?php

namespace App\Oscar\Services;

use App\Oscar\Contracts\FileReaderInterface;

class CsvFileReaderService implements FileReaderInterface
{

    /**
     * Read the whole csv file and return as array of records.
     *
     * @param string $filePath full path of the file.
     * @return array list of records
     */
    public function read(string $filePath):array
    {
        return [];
    }
}