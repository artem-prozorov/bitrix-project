<?php

namespace App\Services\ImageSyncer\Strategies;

use App\Collections\ImagesToLinksCollection;
use App\Services\ImageSyncer\Synchronizer;

class AdditionalImageStrategy implements StrategyInterface
{
    public function getRecord(array $product, string $address, ImagesToLinksCollection $currentImages): array
    {
        $currentImageValueId = $this->getCurrentImageValueId(
            $address, 
            $product[Synchronizer::MORE_PHOTO_CODE], 
            $currentImages
        );

        return [
            'element_id' => $product['ID'],
            'address' => $address,
            'is_main' => false,
            'is_processed' => ($currentImageValueId !== null),
            'current_image_value_id' => $currentImageValueId,
        ];
    }

    /**
     * getCurrentImageValueId.
     *
     * @access	protected
     * @param	string                 	$address      	
     * @param	array                  	$morePhoto    	
     * @param	ImagesToLinksCollection	$currentImages	
     * @return	int|null
     */
    protected function getCurrentImageValueId(
        string $address, 
        array $morePhoto, 
        ImagesToLinksCollection $currentImages
    ): ?int
    {
        foreach ($currentImages as $image) {
            if ($image->getIsMain()) {
                continue;
            }

            if ($image->getAddress() !== $address) {
                continue;
            }

            $currentImageValueId = array_search($image->getFileId(), $morePhoto);

            if ($currentImageValueId === false) {
                continue;
            }

            return (int) $currentImageValueId;
        }

        return null;
    }
}
