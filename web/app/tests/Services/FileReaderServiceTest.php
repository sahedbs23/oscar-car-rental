<?php

namespace Services;

use App\Factory\FileReaderFactory;
use App\Services\FileReaderService;
use PHPUnit\Framework\TestCase;

class FileReaderServiceTest extends TestCase
{
    public ?FileReaderService $fileReaderService;

    public function setUp() :void
    {
        $this->fileReaderService = new FileReaderService();
    }

    public function tearDown() :void
    {
        $this->fileReaderService = null;
    }


    public function listFilesWithExtension():array
    {
        return [
            [__DIR__.'/../../data_source/source-1.csv', 'csv'],
            [__DIR__.'/../../data_source/source-2.json', 'json'],
           [ __DIR__.'/../../data_source/source-3.json', 'json'],
        ];
    }

    public function listFiles():array
    {
        return [
            [__DIR__.'/../../data_source/source-1.csv', true],
            [__DIR__.'/../../data_source/source-2.json', true],
           [ __DIR__.'/../../data_source/source-3.json', true],
           [ __DIR__.'/../../data_source/source-33.json', false],
        ];
    }

    public function test__construct() : void
    {
        $this->assertInstanceOf(FileReaderFactory::class, $this->fileReaderService->fileFactory);
    }

    public function test_listFiles() : void
    {
        $this->assertIsArray($this->fileReaderService->listFiles(''));
    }

    /**
     * @dataProvider listFilesWithExtension
     * @param string $filePath
     * @param string $extension
     * @return void
     */
    public function test_find_file_extension(string $filePath, string $extension) : void
    {
        $this->assertEquals($this->fileReaderService->findFileExtension($filePath), $extension);
    }

    /**
     * @dataProvider listFiles
     * @param string $filePath
     * @param bool $readable
     * @return void
     */
    public function test_fileReadable(string $filePath, bool $readable) : void
    {
        $this->assertEquals($this->fileReaderService->fileReadable($filePath), $readable);
    }

    /**
     * @dataProvider listFilesWithExtension
     * @param string $filePath
     * @param string $extension
     * @return void
     */
    public function testReadFileContent(string $filePath, string $extension) : void
    {
        $this->assertIsArray($this->fileReaderService->readFileContent($extension, $filePath));
    }
}
