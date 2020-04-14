<?php

use PHPUnit\Framework\TestCase;
use App\Services\ImageSyncer\DataObjects\Product;
use App\Services\ImageSyncer\Repository;
use App\Facades\CIBlockElement;
use Mockery;
use Bitrix\Main\ObjectNotFoundException;
use App\Traits\LoadsModules;

class RepositoryTest extends TestCase
{
    use LoadsModules;

    protected $modules = ['iblock'];

    public function setUp(): void
    {
        $this->loadModules();

        CIBlockElement::clearResolvedInstances();
    }

    /**
     * @dataProvider getData
     */
    public function testGetProductById($data)
    {
        $expected = new Product($data);

        $result = $this->createMock(CIBlockResult::class);
        $result->method('Fetch')->willReturn([
            'ID' => $data['ID'], 
            'DETAIL_PICTURE' => $data['DETAIL_PICTURE'],
        ]);

        CIBlockElement::shouldReceive('GetList')
            ->withAny()
            ->andReturn($result);

        $propertyResult = $this->createMock(CIBlockPropertyResult::class);
        $propertyResult->method('Fetch')
            ->willReturn([
                'IBLOCK_ELEMENT_ID' => $data['ID'],
                13 => array_values($data['PROPERTY_MORE_PHOTO_VALUE']),
                29 => array_values($data['PROPERTY_LINKS_TO_IMAGES_VALUE']),
                'PROPERTY_VALUE_ID' => array(
                    13 => array_keys($data['PROPERTY_MORE_PHOTO_VALUE']),
                    29 => array_keys($data['PROPERTY_LINKS_TO_IMAGES_VALUE']),
                ) ,
                'DESCRIPTION' => [],
            ]);

        CIBlockElement::shouldReceive('GetPropertyValues')
            ->withAny()
            ->andReturn($propertyResult);

        $repository = new Repository();

        $product = $repository->getProductById((int) $data['ID']);

        $this->assertEquals($expected, $product);

        Mockery::close();
    }

    public function testExceptionIsThrown()
    {
        $result = $this->createMock(CIBlockResult::class);
        $result->method('Fetch')->willReturn(false);

        CIBlockElement::shouldReceive('GetList')
            ->withAny()
            ->andReturn($result);

        $repository = new Repository();

        $this->expectException(ObjectNotFoundException::class);

        $product = $repository->getProductById(16);
    }

    public function getData(): array
    {
        return [
            [
                [
                    'ID' => 15,
                    'DETAIL_PICTURE' => '70',
                    'PROPERTY_LINKS_TO_IMAGES_VALUE' => [
                        'http://project.local/image73.jpg',
                        'http://project.local/image75.jpg',
                    ],
                    'PROPERTY_MORE_PHOTO_VALUE' => [
                        '18' => '1'
                    ],
                ],
            ],
            [
                [
                    'ID' => 50,
                    'DETAIL_PICTURE' => '7027',
                    'PROPERTY_LINKS_TO_IMAGES_VALUE' => [
                        'http://project.local/image7.jpg',
                    ],
                    'PROPERTY_MORE_PHOTO_VALUE' => [
                        '18' => '15',
                        '19' => '70',
                        '21' => '89',
                    ],
                ],
            ],
        ];
    }
}
