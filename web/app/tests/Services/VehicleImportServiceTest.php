<?php

namespace Services;

use App\Services\FileReaderService;
use App\Services\VehicleImportService;
use PHPUnit\Framework\TestCase;

class VehicleImportServiceTest extends TestCase
{
    /**
     * @var VehicleImportService|null
     */
    public ?VehicleImportService $vehicleImportService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->vehicleImportService = new VehicleImportService();
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->vehicleImportService = null;
    }

    /**
     * data provider for test_readFile
     * @return array[string, int]s
     */
    public function listFiles(): array
    {
        return [
            [__DIR__ . '/../../data_source/source-1.csv', 10],
            [__DIR__ . '/../../data_source/source-2.json', 10],
            [__DIR__ . '/../../data_source/source-222.json', 0] // Undefined path
        ];
    }

    /**
     * @dataProvider listFiles
     * @param string $filePath
     * @param int $records
     * @return void
     */
    public function test_readFile(string $filePath, int $records): void
    {
        $contents = $this->vehicleImportService->readFile($filePath);
        $this->assertIsArray($contents);
        $this->assertEquals(count($contents), $records);
    }

    /**
     * @return void
     */
    public function testFiles(): void
    {
        $vehicleImportService = $this->vehicleImportService->readFiles();
        $this->assertInstanceOf(VehicleImportService::class, $vehicleImportService);
        $this->assertIsArray($vehicleImportService->getVehicles());
    }

    /**
     * @return void
     * @throws \JsonException
     */
    public function test_with_valid_input_to_json(): void
    {
        $contents = $this->vehicleImportService->readFiles()->toJson();
        $this->assertJson($contents);
    }
}
