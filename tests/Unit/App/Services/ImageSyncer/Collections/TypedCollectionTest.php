<?php

use PHPUnit\Framework\TestCase;
use App\Services\ImageSyncer\Collections\ProductCollection;
use App\Services\ImageSyncer\DataObjects\Product;

class TypedCollectionTest extends TestCase
{
    public function testTypedCollection()
    {
        $collection = new ProductCollection();

        $product = new Product([
            'ID' => 9,
            'DETAIL_PICTURE' => 20,
            'PROPERTY_LINKS_TO_IMAGES_VALUE' => [],
            'PROPERTY_MORE_PHOTO_VALUE' => [
                'http://project.local/image1.png'
            ],
        ]);

        $collection->push($product);

        $this->assertEquals(1, $collection->count());
    }

    /**
     * @test
     */
    public function incorrect_object_is_rejected()
    {
        $collection = new ProductCollection();

        $this->expectException(\InvalidArgumentException::class);

        $collection->push('123');
    }
}
