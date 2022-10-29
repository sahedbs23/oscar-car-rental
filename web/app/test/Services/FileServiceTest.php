<?php

namespace AppTest\Oscar\Services;

use App\Oscar\Contracts\FileReaderInterface;
use App\Oscar\Factory\FileReaderFactory;
use App\Oscar\Services\FileService;
use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\TestCase;

class FileServiceTest extends TestCase
{
    private array $supportedFileType;

    private FileReaderFactory $fileReaderFactory;

    private FileService $fileFactory;

    public function setUp(): void
    {
        $this->fileReaderFactory = new fileReaderFactory();
        $this->fileFactory = new FileService();
        $this->supportedFileType = ['csv', 'json'];
    }


    public function testProcessFile(): void
    {
        $files = $this->fileFactory->listFiles('');
        $this->assertIsIterable($files);
        foreach ($files as $file):
            // Check the file is readable
            $this->assertTrue(
                $this
                ->fileFactory
                ->fileReadable($file)
            );
            // File has exact file extension that we want.
            $extension = $this->fileFactory->findFileExtention($file);
            $this->assertContains(
                $extension,
                $this->supportedFileType
            );

            $resource = $this->fileFactory->readFileContent($extension, $file);
//            $this->assertInstanceOf(FileReaderInterface::class, $resource);
//            $object = $resource->toObject();
            $this->assertIsIterable($resource);
        endforeach;
    }

}
