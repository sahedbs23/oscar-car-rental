<?php

namespace AppTest\Oscar\Factory;

use App\Oscar\Contracts\FileReaderInterface;
use App\Oscar\Factory\FileReaderFactory;
use App\Oscar\Services\CsvFileReaderService;
use App\Oscar\Services\JsonFileReaderService;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class FileReaderFactoryTest extends TestCase
{
    protected FileReaderFactory $factory;

    public function setUp(): void
    {
        $this->factory = new FileReaderFactory();
    }

    /**
     *
     * @return void
     */
    public function testcreate(): void
    {
        $factory = $this->factory->create('csv');
        $this->assertInstanceOf(FileReaderInterface::class, $factory);
        $this->assertInstanceOf(CsvFileReaderService::class, $factory);

        $factory = $this->factory->create('json');
        $this->assertInstanceOf(FileReaderInterface::class, $factory);
        $this->assertInstanceOf(JsonFileReaderService::class, $factory);

        $this->expectException(RuntimeException::class);
        $this->factory->create('unknown');
    }
}
