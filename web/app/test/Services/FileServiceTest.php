<?php

namespace AppTest\Oscar\Services;

use App\Oscar\Factory\FileReaderFactory;
use App\Oscar\Services\FileService;
use PHPUnit\Framework\TestCase;

class FileServiceTest extends TestCase
{
    private FileReaderFactory $fileReaderFactory;

    private FileService $fileFactory;

    public function setUp() :void
    {

        $this->fileReaderFactory = new fileReaderFactory();
        $this->fileFactory = new FileService();
    }

    public function testFileReadable()
    {
        $this->assertTrue($this->fileFactory->fileReadable('/var/www/html/app/data_source'));
    }
}
