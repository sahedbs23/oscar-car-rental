<?php

namespace App\Services;

use App\Helpers\ReadConfig;
use JsonException;

class VehicleImportService
{
    /**
     * @var array
     */
    private array $vehicles;

    /**
     * @var string[]
     */
    public array $supportedFileType;

    /**
     * @var FileReaderService
     */
    private FileReaderService $fileFactory;

    public function __construct()
    {
        $this->fileFactory = new FileReaderService();
        $this->supportedFileType = ReadConfig::config('file_system', 'supported_file_type');
    }

    /**
     * @param string $directory
     * @return void
     */
    public function importDatFromSource(string $directory = ''): void
    {
        $service = new VehicleService();
        $files = $this->fileFactory->listFiles($directory);

        //TODO:: Instead of loop Write insert many function.
        foreach ($files as $filePath):
            $fileContents = $this->readFile($filePath);
            foreach ($fileContents as $content):
                $arr = (array)$content;
                $service->storeCar($this->removeEmptyValueFromArray($arr));
            endforeach;
        endforeach;
    }

    public function readFiles(string $directory = ''): static
    {
        $vehicleToMerge = [];
        $files = $this->fileFactory->listFiles($directory);
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
        if ($isReadable &&
            in_array($extension = $this->fileFactory->findFileExtension($filePath), $this->supportedFileType, true)) {
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

    /**
     * @return array
     */
    public function getVehicles(): array
    {
        return $this->vehicles;
    }

    /**
     * @param array $input
     * @return array
     */
    private function removeEmptyValueFromArray(array $input): array
    {
        return array_filter($input, static function ($item) {
            return !empty($item);
        });
    }
}