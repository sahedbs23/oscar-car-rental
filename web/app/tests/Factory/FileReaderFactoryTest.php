<?php

namespace Tests\Factory;

use App\Factory\FileReaderFactory;
use App\Services\CsvFileReaderService;
use App\Services\JsonFileReaderService;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class FileReaderFactoryTest extends TestCase
{
    protected ?FileReaderFactory $factory;

    public function setUp(): void
    {
        parent::setUp();
        $this->factory = new FileReaderFactory();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->factory = null;
    }

    /**
     *
     * @return void
     */
    public function test_can_create_csv_file_reader_instance(): void
    {
        $factory = $this->factory->create('csv');
        $this->assertInstanceOf(CsvFileReaderService::class, $factory);
    }

    /**
     *
     * @return void
     */
    public function test_can_create_json_file_reader_instance(): void
    {
        $factory = $this->factory->create('json');
        $this->assertInstanceOf(JsonFileReaderService::class, $factory);
    }


    /**
     *
     * @return void
     */
    public function test_can_throwException_on_unsupported_file_type(): void
    {
        $this->expectException(RuntimeException::class);
        $this->factory->create('unknown');
    }
}
