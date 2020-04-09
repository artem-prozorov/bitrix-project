<?php

namespace App\Services\ImageSyncer\Strategies;

use App\Collections\ImagesToLinksCollection;
use App\Services\ImageSyncer\DataObjects\{Product, RecordToProcess};

class MainImageStrategy implements StrategyInterface
{
    public function getRecord(Product $product, string $address, ImagesToLinksCollection $currentImages): RecordToProcess
    {
        return new RecordToProcess([
            'element_id' => $product->getId(),
            'address' => $address,
            'is_main' => true,
            'current_image_value_id' => null,
            'is_processed' => $this->isMainImageProcessed(
                (int) $product->getDetailPictureId(),
                $currentImages
            ),
        ]);
    }

    /**
     * isMainImageProcessed.
     *
     * @access	protected
     * @param	int                    	$fileId       	
     * @param	ImagesToLinksCollection	$currentImages	
     * @return	boolean
     */
    protected function isMainImageProcessed(int $fileId, ImagesToLinksCollection $currentImages): bool
    {
        foreach ($currentImages as $image) {
            if ($image->getIsMain() && $image->getFileId() === $fileId) {
                return true;
            }
        }

        return false;
    }
}
