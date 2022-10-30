<?php

namespace AppTest\Oscar\Services;

use App\Oscar\Services\CsvFileReaderService;
use PHPUnit\Framework\TestCase;

class CsvFileReaderServiceTest extends TestCase
{
    public ?CsvFileReaderService $csvFileReaderService;

    public function setUp() : void
    {
        //parent::setUp();
        $this->csvFileReaderService  = new CsvFileReaderService();
    }

    public function tearDown() :void
    {
        //parent::tearDown();
        $this->csvFileReaderService  = null;
    }

    /**
     * @return string
     */
    public function findCsvFileForTest(): string
    {
        return __DIR__.'/../../../app/data_source/source-1.csv';
    }

    /**
     * @dataProvider findCsvFileForTest
     * @param string $filePath
     * @return void
     */
    public function testRead(string $filePath) : void
    {
        $source = $this->csvFileReaderService->read($filePath);
        $this->assertIsIterable($this->csvFileReaderService->getData());
        $this->assertInstanceOf( CsvFileReaderService::class, $source);

    }
}
