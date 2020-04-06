<?php

namespace App\Services\ImageSyncer;

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

            $strategy = Strategies\StrategyFactory::getStrategy($key);

            $data[] = $strategy->getRecord($product, $address, $currentImages);
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
}
