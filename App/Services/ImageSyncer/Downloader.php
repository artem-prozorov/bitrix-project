<?php

namespace App\Services\ImageSyncer;

use App\Services\ImageSyncer\DataObjects\RecordToProcess;
use App\Services\ImageSyncer\Exceptions\{FileNotDownloaded, FileNotSaved};
use App\Base\FileManager;

class Downloader
{
    protected $client;

    protected $fileManager;

    public function __construct(SimpleClient $client, FileManager $fileManager)
    {
        $this->client = $client;
        $this->fileManager = $fileManager;
    }

    /**
     * getDownloadedFilePath.
     *
     * @access	public
     * @param	RecordToProcess	$record	
     * @return	string
     */
    public function getDownloadedFilePath(RecordToProcess $record): string
    {
        $contents = $this->client->getContents($record->getAddress());

        if (empty($contents)) {
            throw new FileNotDownloaded('Не смогли скачать файл ' . $record->getAddress());
        }

        $tempFilePath = $this->fileManager->getTempFileName();

        if (empty($this->fileManager->putContents($tempFilePath, $contents))) {
            throw new FileNotSaved('Не смогли записать данные во временный файл');
        }

        return $tempFilePath;
    }
}
