<?php

namespace App\Oscar\Services;

use JsonException;

class VehicleImportService
{
    public array $vehicles;
    /**
     * @var string[]
     */
    private array $supportedFileType;

    /**
     * @var FileReaderService
     */
    private FileReaderService $fileFactory;

    public function __construct()
    {
        $this->fileFactory = new FileReaderService();
        $this->supportedFileType = ['csv', 'json'];
    }

    public function readFiles(): static
    {
        $vehicleToMerge = [];
        $files = FileReaderService::listFiles('');
        foreach ($files as $filePath):
            $vehicleToMerge[] = $this->readFile($filePath);
        endforeach;
        $vehicles = array_merge([], ...$vehicleToMerge);
        $this->vehicles = $vehicles ?? [];
        return $this;
    }

    /**
     * @param string $filePath
     * @return array
     */
    public function readFile(string $filePath): array
    {
        $isReadable = $this
            ->fileFactory
            ->fileReadable($filePath);

        // File is readable and
        // File has exact file extension that we want.
        if ( $isReadable &&
            in_array($extension = $this->fileFactory->findFileExtention($filePath), $this->supportedFileType, true)) {
            return $this->fileFactory->readFileContent($extension, $filePath);
        }
        return [];
    }

    /**
     * @throws JsonException
     */
    public function toJson(): bool|string
    {
        return json_encode($this->vehicles, JSON_THROW_ON_ERROR);
    }
}