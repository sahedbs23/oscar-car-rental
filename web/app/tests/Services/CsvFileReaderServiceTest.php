<?php

namespace Services;

use App\Services\CsvFileReaderService;
use PHPUnit\Framework\TestCase;

class CsvFileReaderServiceTest extends TestCase
{
    /**
     * @var CsvFileReaderService|null
     */
    public ?CsvFileReaderService $csvFileReaderService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->csvFileReaderService = new CsvFileReaderService();
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $this->csvFileReaderService = null;
    }

    /**
     * @return string[]
     */
    public function validCsvFileWithCarsData(): array
    {
        return [
            [__DIR__ . '/../../../app/data_source/source-1.csv'],
        ];
    }

    /**
     * @return string[]
     */
    public function invalidCsvFileWithCarsData(): array
    {
        return [
            [__DIR__ . '/../../../app/data_source/source-12.csv'],
        ];
    }

    /**
     * @dataProvider validCsvFileWithCarsData
     * @param string $filepath
     * @return void
     */
    public function testReadWithValidaInput(string $filepath): void
    {
        $source = $this->csvFileReaderService->read($filepath);
        $this->assertInstanceOf(CsvFileReaderService::class, $source);
        $this->assertNotEmpty($this->csvFileReaderService->getData());
    }

    /**
     * @dataProvider invalidCsvFileWithCarsData
     * @param $filepath
     * @return void
     */
    public function testReadWithInValidaInput($filepath): void
    {
        $this->expectWarning();
        $this->csvFileReaderService->read($filepath);
    }

    /**
     * @dataProvider validCsvFileWithCarsData
     * @param string $filepath
     * @return void
     */
    public function test_transform(string $filepath)
    {
        $csvFileReaderService = $this->csvFileReaderService->read($filepath);
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
