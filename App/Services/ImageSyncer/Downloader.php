<?php

namespace App\Services\ImageSyncer;

class Downloader
{
    public function downloadImage(string $address): string
    {
        $contents = file_get_contents($record->getAddress());
        $tempFile = $this->createTempFile();
        file_put_contents($tempFile, $contents);
    }
}
