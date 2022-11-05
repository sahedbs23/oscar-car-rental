<?php

namespace Services;

use App\Services\JsonFileReaderService;
use PHPUnit\Framework\TestCase;

class JsonFileReaderServiceTest extends TestCase
{
    /**
     * @var JsonFileReaderService|null
     */
    public ?JsonFileReaderService $jsonFileReaderService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->jsonFileReaderService = new JsonFileReaderService();
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $this->jsonFileReaderService = null;
    }

    /**
     * @return string[]
     */
    public function validCsvFileWithCarsData(): array
    {
        return [
            [__DIR__ . '/../../../app/data_source/source-2.json'],
            [__DIR__ . '/../../../app/data_source/source-3.json'],
        ];
    }

    /**
     * @return string[]
     */
    public function invalidCsvFileWithCarsData(): array
    {
        return [
            [__DIR__ . '/../../../app/data_source/source-12.json'],
        ];
    }

    /**
     * @dataProvider validCsvFileWithCarsData
     * @param string $filepath
     * @return void
     * @throws \JsonException
     */
    public function testReadWithValidaInput(string $filepath): void
    {
        $source = $this->jsonFileReaderService->read($filepath);
        $this->assertInstanceOf(JsonFileReaderService::class, $source);
        $this->assertNotEmpty($this->jsonFileReaderService->getData());
    }

    /**
     * @dataProvider invalidCsvFileWithCarsData
     * @param $filepath
     * @return void
     * @throws \JsonException
     */
    public function testReadWithInValidaInput($filepath): void
    {
        $this->expectWarning();
        $this->jsonFileReaderService->read($filepath);
    }

    /**
     * @dataProvider validCsvFileWithCarsData
     * @param string $filepath
     * @return void
     * @throws \JsonException
     */
    public function test_transform(string $filepath)
    {
        $csvFileReaderService = $this->jsonFileReaderService->read($filepath);
        $data = $csvFileReaderService->getData();
        $this->assertIsIterable($data);
        $this->assertNotEmpty($data);
        $transformedData = $csvFileReaderService->transform();
        $this->assertIsArray($transformedData);

        foreach ($transformedData as $row):
            $this->assertIsObject($row);
            $this->assertObjectHasAttribute('location', $row);
            $this->assertObjectHasAttribute('car_brand', $row);
            $this->assertObjectHasAttribute('car_model', $row);
            $this->assertObjectHasAttribute('license_plate', $row);
            $this->assertObjectHasAttribute('car_year', $row);
            $this->assertObjectHasAttribute('number_of_doors', $row);
            $this->assertObjectHasAttribute('number_of_seats', $row);
            $this->assertObjectHasAttribute('fuel_type', $row);
            $this->assertObjectHasAttribute('transmission', $row);
            $this->assertObjectHasAttribute('car_type_group', $row);
            $this->assertObjectHasAttribute('car_type', $row);
            $this->assertObjectHasAttribute('car_km', $row);
            $this->assertObjectHasAttribute('inside_height', $row);
            $this->assertObjectHasAttribute('inside_length', $row);
            $this->assertObjectHasAttribute('inside_width', $row);
        endforeach;
    }
}
