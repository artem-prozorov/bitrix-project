<?php

namespace App\Services\ImageSyncer;

use App\Services\ImageSyncer\DataObjects\RecordToProcess;
use App\Services\ImageSyncer\Exceptions\{FileNotDownloaded, FileNotSaved};
use App\Facades\FileManager;

class Downloader
{
    protected $client;

    public function __construct(SimpleClient $client)
    {
        $this->client = $client;
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

        $tempFilePath = FileManager::getTempFileName();
        if (empty(FileManager::putContents($tempFilePath, $contents))) {
            throw new FileNotSaved('Не смогли записать данные во временный файл');
        }

        $info = FileManager::getFileArray($tempFilePath);
        if (strpos($info['type'], 'image') === false) {
            throw new \Exception('Not an image!');
        }

        $extension = $this->getExtension($info['type']);

        FileManager::rename($tempFilePath, $tempFilePath . '.' . $extension);

        return $tempFilePath . '.' . $extension;
    }

    /**
     * getExtension.
     *
     * @access	protected
     * @param	string	$mime
     * @return	string
     */
    protected function getExtension(string $mime): string
    {
        $info = explode('/', $mime);

        if (empty($info[1])) {
            throw new \InvalidArgumentException('Не удалось определить расширение файла');
        }

        return $info[1];
    }
}
