<?php

use Mockery\Adapter\Phpunit\MockeryTestCase;
use App\Services\ImageSyncer\DataObjects\{Product, RecordToProcess};
use App\Services\ImageSyncer\Repository;
use App\Facades\{CIBlockElement, FileManager};
use Mockery;
use Bitrix\Main\ObjectNotFoundException;
use App\Traits\LoadsModules;
use App\Services\ImageSyncer\Collections\RecordsToProcessCollection;

class RepositoryTest extends MockeryTestCase
{
    use LoadsModules;

    protected $modules = ['iblock'];

    public function setUp(): void
    {
        parent::setUp();

        $this->loadModules();

        CIBlockElement::clearResolvedInstances();
    }

    /**
     * @test
     * @dataProvider getProductData
     */
    public function repository_returns_correct_product($data)
    {
        $expected = new Product($data);

        $result = $this->createMock(CIBlockResult::class);
        $result->method('Fetch')->willReturn([
            'ID' => $data['ID'], 
            'DETAIL_PICTURE' => $data['DETAIL_PICTURE'],
        ]);

        CIBlockElement::shouldReceive('GetList')->withAny()->andReturn($result);

        $propertyResult = $this->createMock(CIBlockPropertyResult::class);
        $propertyResult->method('Fetch')->willReturn($this->getFormattedFetchedData($data));

        CIBlockElement::shouldReceive('GetPropertyValues')->withAny()->andReturn($propertyResult);

        $product = (new Repository())->getProductById((int) $data['ID']);

        $this->assertEquals($expected, $product);
    }

    /**
     * @test
     */
    public function repository_throws_exception_if_no_product_found()
    {
        $result = $this->createMock(CIBlockResult::class);
        $result->method('Fetch')->willReturn(false);

        CIBlockElement::shouldReceive('GetList')->withAny()->andReturn($result);

        $repository = new Repository();

        $this->expectException(ObjectNotFoundException::class);

        $product = $repository->getProductById(16);
    }

    /**
     * @test
     */
    public function repository_sets_main_image_if_needed()
    {
        $records = new RecordsToProcessCollection([
            new RecordToProcess([
                'element_id' => 50,
                'address' => 'http://project.local/image1.jpg',
                'is_main' => true,
                'is_processed' => false,
                'current_image_value_id' => null,
                'temp_file_path' => '/tmp/file',
            ]),
        ]);

        CIBlockElement::shouldReceive('Update')->once()->andReturn(true);
        CIBlockElement::shouldReceive('SetPropertyValues')->never();
        FileManager::shouldReceive('getFileArray')->once()->andReturn(['image data here']);

        (new Repository())->updateImages($this->getProduct(['http://project.local/image7.jpg']), $records);
    }

    /**
     * @test
     */
    public function repository_doesnt_set_main_image_if_not_needed()
    {
        $records = new RecordsToProcessCollection([
            new RecordToProcess([
                'element_id' => 50,
                'address' => 'http://project.local/image1.jpg',
                'is_main' => true,
                'is_processed' => true,
                'current_image_value_id' => null,
            ]),
        ]);

        CIBlockElement::shouldReceive('Update')->never();
        CIBlockElement::shouldReceive('SetPropertyValues')->never();

        (new Repository())->updateImages($this->getProduct(['http://project.local/image7.jpg']), $records);
    }

    /**
     * @test
     */
    public function repository_sets_correct_images()
    {
        $product = $this->getProduct(
            [
                'http://project.local/image7.jpg',
                'http://project.local/image17.jpg',
                'http://project.local/image27.jpg',
            ],
            [
                '12' => '7027',
                '26' => '17',
                '30' => '24',
            ]
        );

        $records = new RecordsToProcessCollection([
            new RecordToProcess([
                'element_id' => 50,
                'address' => 'http://project.local/image7.jpg',
                'is_main' => true,
                'is_processed' => true,
                'current_image_value_id' => null,
            ]),
            new RecordToProcess([
                'element_id' => 50,
                'address' => 'http://project.local/image17.jpg',
                'is_main' => false,
                'is_processed' => true,
                'current_image_value_id' => 26,
            ]),
            new RecordToProcess([
                'element_id' => 50,
                'address' => 'http://project.local/image27.jpg',
                'is_main' => false,
                'is_processed' => false,
                'current_image_value_id' => null,
                'temp_file_path' => '/tmp/file',
            ]),
        ]);

        FileManager::shouldReceive('getFileArray')->once()->andReturn(['image data here']);

        $repository = new Repository();

        CIBlockElement::shouldReceive('Update')->never();
        CIBlockElement::shouldReceive('SetPropertyValues')
            ->once()
            ->with(
                50,
                Repository::IBLOCK_ID,
                [['image data here'], 12 => ['del' => 'Y'], 30 => ['del' => 'Y']],
                'MORE_PHOTO'
            );

        $repository->updateImages($product, $records);
    }

    protected function getFormattedFetchedData(array $data): array
    {
        return [
            'IBLOCK_ELEMENT_ID' => $data['ID'],
            13 => array_values($data['PROPERTY_MORE_PHOTO_VALUE']),
            29 => array_values($data['PROPERTY_LINKS_TO_IMAGES_VALUE']),
            'PROPERTY_VALUE_ID' => array(
                13 => array_keys($data['PROPERTY_MORE_PHOTO_VALUE']),
                29 => array_keys($data['PROPERTY_LINKS_TO_IMAGES_VALUE']),
            ) ,
            'DESCRIPTION' => [],
        ];
    }

    public function getProductData(): array
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

    protected function getProduct(array $linksToImages = [], array $morePhotos = []): Product
    {
        return new Product([
            'ID' => 50,
            'DETAIL_PICTURE' => '7027',
            'PROPERTY_LINKS_TO_IMAGES_VALUE' => $linksToImages,
            'PROPERTY_MORE_PHOTO_VALUE' => $morePhotos,
        ]);
    }
}
