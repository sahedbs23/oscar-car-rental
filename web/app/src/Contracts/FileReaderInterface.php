<?php

namespace App\Oscar\Contracts;

interface FileReaderInterface
{
    /**
     * Read the whole json file and return as array of records.
     *
     * @param string $filePath full path of the file.
     * @return FileReaderInterface list of records
     */
    public function read(string $filePath): static;

    /**
     * convert the data to iterable collections.
     *
     * @return array
     */
    public function transform() : array;
}