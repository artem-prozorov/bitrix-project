<?php

use PHPUnit\Framework\TestCase;
use App\Services\ImageSyncer\DataObjects\Product;

class ProductTest extends TestCase
{
    public function testValidation()
    {
        $data = [];

        $this->expectException(\InvalidArgumentException::class);

        $product = new Product($data);
    }

    public function testCorrectCreation()
    {
        $initialData = [
            'ID' => 9,
            'DETAIL_PICTURE' => 20,
            'PROPERTY_LINKS_TO_IMAGES_VALUE' => [],
            'PROPERTY_MORE_PHOTO_VALUE' => [
                'http://project.local/image1.png'
            ],
        ];

        $product = new Product($initialData);

        $this->assertEquals($initialData['ID'], $product->getId());
        $this->assertEquals($initialData['DETAIL_PICTURE'], $product->getDetailPictureId());
        $this->assertEquals($initialData['PROPERTY_LINKS_TO_IMAGES_VALUE'], $product->getLinksToImages());
        $this->assertEquals($initialData['PROPERTY_MORE_PHOTO_VALUE'], $product->getMorePhotos());
    }
}
