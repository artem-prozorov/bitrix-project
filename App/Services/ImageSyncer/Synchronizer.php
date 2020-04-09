<?php

namespace App\Services\ImageSyncer;

use App\Collections\ImagesToLinksCollection;
use App\Services\ImageSyncer\DataObjects\Product;
use App\Services\ImageSyncer\Collections\RecordsToProcessCollection;

class Synchronizer
{
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

            $data->push(
                $strategy->getRecord($product, $address, $currentImages)
            );
        }

        return $data;
    }
}
