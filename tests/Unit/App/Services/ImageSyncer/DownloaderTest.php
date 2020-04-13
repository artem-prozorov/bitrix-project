<?php

use PHPUnit\Framework\TestCase;
use App\Services\ImageSyncer\Downloader;
use App\Services\ImageSyncer\Exceptions\{FileNotDownloaded, FileNotSaved};
use App\Services\ImageSyncer\SimpleClient;
use App\Facades\FileManager;
use App\Services\ImageSyncer\DataObjects\RecordToProcess;

class DownloaderTest extends TestCase
{
    protected $record;

    public function setUp(): void
    {
        FileManager::clearResolvedInstances();

        $this->record = new RecordToProcess([
            'element_id' => 12,
            'address' => 'http://project.local/image1.jpg',
            'is_main' => true,
            'is_processed' => true,
            'current_image_value_id' => null,
        ]);
    }

    /**
     * @test
     */
    public function it_throws_exception_if_cannot_download_file()
    {
        $client = $this->createMock(SimpleClient::class);
        $client->method('getContents')->willReturn(false);

        $downloader = new Downloader($client);

        $this->expectException(FileNotDownloaded::class);

        $downloader->getDownloadedFilePath($this->record);
    }

    /**
     * @test
     */
    public function it_throws_exception_if_cannot_save_file()
    {
        $client = $this->createMock(SimpleClient::class);
        $client->method('getContents')->willReturn('contents');

        FileManager::shouldReceive('putContents')->andReturn(false);
        FileManager::shouldReceive('getTempFileName')->willReturn('/tmp/tempfile');

        $downloader = new Downloader($client);

        $this->expectException(FileNotSaved::class);

        $downloader->getDownloadedFilePath($this->record);
    }

    /**
     * @test
     */
    public function it_returns_path_to_temp_file()
    {
        $expected = '/tmp/tempfile';

        $client = $this->createMock(SimpleClient::class);
        $client->method('getContents')->willReturn('contents');

        FileManager::shouldReceive('putContents')->andReturn(12);
        FileManager::shouldReceive('getTempFileName')->andReturn($expected);

        $downloader = new Downloader($client);

        $path = $downloader->getDownloadedFilePath($this->record);

        $this->assertEquals($expected, $path);
    }
}
