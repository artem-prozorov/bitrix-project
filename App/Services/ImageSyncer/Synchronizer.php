<?php

namespace App\Services\ImageSyncer;

use App\Collections\ImagesToLinksCollection;
use App\Services\ImageSyncer\DataObjects\Product;
use App\Services\ImageSyncer\Collections\RecordsToProcessCollection;

class Synchronizer
{
    /**
     * @var		Downloader	$downloader
     */
    protected Downloader $downloader;

    public function __construct(Downloader $downloader)
    {
        $this->downloader = $downloader;
    }

    /**
     * Возвращает массив, описывающий файлы, которые необходимо обработать
     *
     * @access	public
     * @param	Product                  	$product      	
     * @param	ImagesToLinksCollection	$currentImages	
     * @return	array
     */
    public function getImagesToProcess(Product $product, ImagesToLinksCollection $currentImages): RecordsToProcessCollection
    {
        $data = new RecordsToProcessCollection();

        foreach ($product->getLinksToImages() as $key => $address) {

            $strategy = Strategies\StrategyFactory::getStrategy($key);

            $record = $strategy->getRecord($product, $address, $currentImages);

            if (! $record->isProcessed()) {
                $path = $this->downloader->downloadImage($record->getAddress());

                $record->setTempFilePath($path);
            }

            $data->push($record);
        }

        return $data;
    }
}
