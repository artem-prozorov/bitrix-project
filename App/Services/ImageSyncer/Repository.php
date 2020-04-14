<?php

namespace App\Services\ImageSyncer;

use App\Services\ImageSyncer\DataObjects\Product;
use App\Facades\{CIBlockElement, FileManager};
use Bitrix\Main\ObjectNotFoundException;
use App\Traits\LoadsModules;
use App\Datamanagers\ImagesToLinksTable;
use App\Collections\ImagesToLinksCollection;
use App\Services\ImageSyncer\Collections\RecordsToProcessCollection;

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

    /**
     * getImagesForProduct.
     *
     * @access	public
     * @param	int	$productId	
     * @return	ImagesToLinksCollection
     */
    public function getImagesForProduct(int $productId): ImagesToLinksCollection
    {
        return ImagesToLinksTable::getList(['filter' => ['ELEMENT_ID' => $productId]])->fetchCollection();
    }

    /**
     * updateImages.
     *
     * @access	public
     * @param	Product                   	$product	
     * @param	RecordsToProcessCollection	$records	
     * @return	void
     */
    public function updateImages(Product $product, RecordsToProcessCollection $records): void
    {
        $newValues = [];
        $currentIds = [];

        foreach ($records as $record) {
            if (! empty($record->getCurrentImageValueId())) {
                $currentIds[] = $record->getCurrentImageValueId();
            }

            if ($record->isProcessed()) {
                continue;
            }

            if ($record->isMain()) {
                $this->updateMainImage($product, $record);
                continue;
            }

            $newValues[] = FileManager::getFileArray($record->getTempFilePath());
        }

        foreach (array_keys($product->getMorePhotos()) as $currentId) {
            if (! in_array($currentId, $currentIds)) {
                $newValues[$currentId] = ["del" => "Y"];
            }
        }

        if (empty($newValues)) {
            return;
        }

        CIBlockElement::SetPropertyValues($product->getId(), static::IBLOCK_ID, $newValues, 'MORE_PHOTO');
    }

    

    protected function updateMainImage(Product $product, $record)
    {
        $fileData = FileManager::getFileArray($record->getTempFilePath());

        CIBlockElement::Update($product->getId(), ['DETAIL_PICTURE' => $fileData]);
    }
}
