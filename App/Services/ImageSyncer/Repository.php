<?php

namespace App\Services\ImageSyncer;

use App\Services\ImageSyncer\DataObjects\Product;
use App\Facades\CIBlockElement;
use Bitrix\Main\ObjectNotFoundException;
use App\Traits\LoadsModules;

class Repository
{
    use LoadsModules;

    public const IBLOCK_ID = 2;

    public const MORE_PHOTO_PROPERTY_ID = 13;

    public const LINK_TO_IMAGES_PROPERTY_ID = 29;

    protected $modules = ['iblock'];

    public function __construct()
    {
        $this->loadModules();
    }

    /**
     * getProductById.
     *
     * @access	public
     * @param	int	$productId	
     * @return	Product
     */
    public function getProductById(int $productId): Product
    {
        $select = ["ID", "DETAIL_PICTURE"];
        $filter = ["IBLOCK_ID" => static::IBLOCK_ID, "ID" => $productId];
        $query = CIBlockElement::GetList([], $filter, false, false, $select);
        $rawProduct = $query->Fetch();
        if (empty($rawProduct)) {
            throw new ObjectNotFoundException('Продукт с идентификатором ' . $productId . ' не существует');
        }

        $propertyFilter = ['ID' => [static::LINK_TO_IMAGES_PROPERTY_ID, static::MORE_PHOTO_PROPERTY_ID]];
        $propertyQuery = CIBlockElement::GetPropertyValues(static::IBLOCK_ID, $filter, true, $propertyFilter);
        $row = $propertyQuery->Fetch();
        $morePhotos = array_combine(
            $row['PROPERTY_VALUE_ID'][static::MORE_PHOTO_PROPERTY_ID], 
            $row[static::MORE_PHOTO_PROPERTY_ID]
        );

        $rawProduct['PROPERTY_MORE_PHOTO_VALUE'] = $morePhotos;
        $rawProduct['PROPERTY_LINKS_TO_IMAGES_VALUE'] = $row[static::LINK_TO_IMAGES_PROPERTY_ID];

        return new Product($rawProduct);
    }
}
