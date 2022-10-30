<?php

namespace AppTest\Oscar\Services;

use App\Oscar\Services\FileReaderService;
use PHPUnit\Framework\TestCase;

class VehicleImportServiceTest extends TestCase
{
    private array $supportedFileType;

    private FileReaderService $fileFactory;

    public function setUp(): void
    {
        $this->fileFactory = new FileReaderService();
        $this->supportedFileType = ['csv', 'json'];
    }


    public function testProcessFile(): void
    {
//        $files = FileReaderService::listFiles('');
//        $this->assertIsIterable($files);
//        foreach ($files as $file):
//            // Check the file is readable
//            $isReadable = $this
//                ->fileFactory
//                ->fileReadable($file);
//
//            $this->assertIsBool($isReadable);
//
//            if (!$isReadable) {
//                continue;
//            }
//            // File has exact file extension that we want.
//            $extension = $this->fileFactory->findFileExtention($file);
//
//            $this->assertContains(
//                $extension,
//                $this->supportedFileType
//            );
//            $resource = $this->fileFactory->readFileContent($extension, $file);
//
//            $this->assertIsIterable($resource);
//
//            foreach ($resource as $r):
//                $this->assertObjectHasAttribute('location', $r);
//            endforeach;
//
//        endforeach;
    }

}
