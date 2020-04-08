<?php

namespace App\Services\ImageSyncer;

use App\Collections\ImagesToLinksCollection;
use App\Services\ImageSyncer\DataObjects\Product;

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
    public function getImagesToProcess(Product $product, ImagesToLinksCollection $currentImages): array
    {
        $data = [];

        foreach ($product->getLinksToImages() as $key => $address) {

            $strategy = Strategies\StrategyFactory::getStrategy($key);

            $data[] = $strategy->getRecord($product, $address, $currentImages);
        }

        return $data;
    }
}
