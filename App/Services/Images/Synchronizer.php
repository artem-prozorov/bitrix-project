<?php

namespace App\Services\Images;

use App\Collections\ImagesToLinksCollection;

class Synchronizer
{
    public const LINKS_TO_IMAGES_CODE = 'PROPERTY_LINKS_TO_IMAGES_VALUE';

    public const MORE_PHOTO_CODE = 'PROPERTY_MORE_PHOTO_VALUE';

    public const DETAIL_PICTURE = 'DETAIL_PICTURE';

    /**
     * Возвращает массив, описывающий файлы, которые необходимо обработать
     *
     * @access	public
     * @param	array                  	$product      	
     * @param	ImagesToLinksCollection	$currentImages	
     * @return	array
     */
    public function getImagesToProcess(array $product, ImagesToLinksCollection $currentImages): array
    {
        $this->validateProductArray($product);

        $data = [];

        foreach ($product[static::LINKS_TO_IMAGES_CODE] as $key => $address) {
            $isMain = $key === 0 ? true : false;

            $record = [
                'element_id' => $product['ID'],
                'address' => $address,
                'is_main' => $isMain,
                'current_image_value_id' => null,
            ];

            if ($isMain) {
                $record['is_processed'] = $this->isMainImageProcessed(
                    (int) $product[static::DETAIL_PICTURE], 
                    $currentImages
                );
            } else {
                $currentImageValueId = $this->getCurrentImageValueId(
                    $address, 
                    $product[static::MORE_PHOTO_CODE], 
                    $currentImages
                );

                $record['is_processed'] = ($currentImageValueId !== null);
                $record['current_image_value_id'] = $currentImageValueId;
            }

            $data[] = $record;
        }

        return $data;
    }

    /**
     * Проверяет валидность массива продукта
     *
     * @access	protected
     * @param	array	$product	
     * @return	void
     */
    protected function validateProductArray(array $product): void
    {
        if (! array_key_exists('ID', $product)) {
            throw new \InvalidArgumentException(
                'Массив $product должен содержать идентификатор'
            );
        }

        if (! array_key_exists(static::LINKS_TO_IMAGES_CODE, $product)) {
            throw new \InvalidArgumentException(
                'Массив $product должен содержать поле ' . static::LINKS_TO_IMAGES_CODE
            );
        }

        if (! array_key_exists(static::DETAIL_PICTURE, $product)) {
            throw new \InvalidArgumentException(
                'Массив $product должен содержать поле ' . static::DETAIL_PICTURE
            );
        }

        if (! array_key_exists(static::MORE_PHOTO_CODE, $product)) {
            throw new \InvalidArgumentException(
                'Массив $product должен содержать поле ' . static::MORE_PHOTO_CODE
            );
        }
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
