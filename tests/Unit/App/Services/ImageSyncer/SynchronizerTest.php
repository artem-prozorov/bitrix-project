<?php

use PHPUnit\Framework\TestCase;
use App\Services\ImageSyncer\{Downloader, Synchronizer};
use App\Collections\ImagesToLinksCollection;
use App\Models\ImageToLink;
use App\Services\ImageSyncer\DataObjects\{Product, RecordToProcess};
use App\Services\ImageSyncer\Collections\RecordsToProcessCollection;

class SynchronzierTest extends TestCase
{
    /**
     * @test
     * @dataProvider getData
     */
    public function synchronizer_returns_correct_images_to_process($productData, $images, $expectedData)
    {
        $currentImages = new ImagesToLinksCollection();

        foreach ($images as $image) {
            $currentImages->add(new ImageToLink($image));
        }

        $downloader = $this->createMock(Downloader::class);
        $downloader->method('getDownloadedFilePath')->willReturn('/tmp/file.jpg');

        $synchronizer = new Synchronizer($downloader);

        $product = new Product($productData);

        $imagesToProcess = $synchronizer->getImagesToProcess($product, $currentImages);

        $expected = new RecordsToProcessCollection($expectedData);

        $this->assertEquals($expected, $imagesToProcess);
    }

    public function getData(): array
    {
        return [
            // Первый набор данных
            [
                [
                    'ID' => 12,
                    'DETAIL_PICTURE' => '',
                    'PROPERTY_LINKS_TO_IMAGES_VALUE' => null,
                    'PROPERTY_MORE_PHOTO_VALUE' => null,
                ],
                [],
                [],
            ],

            // Второй набор данных
            [
                [
                    'ID' => 12,
                    'DETAIL_PICTURE' => '1',
                    'PROPERTY_LINKS_TO_IMAGES_VALUE' => ['http://project.local/image1.jpg'],
                    'PROPERTY_MORE_PHOTO_VALUE' => null,
                ],
                [
                    [
                        'ID' => 1,
                        'FILE_ID' => 1,
                        'ELEMENT_ID' => 12,
                        'ADDRESS' =>'http://project.local/image1.jpg',
                        'IS_MAIN' => true,
                    ],
                ],
                [
                    new RecordToProcess([
                        'element_id' => 12,
                        'address' => 'http://project.local/image1.jpg',
                        'is_main' => true,
                        'is_processed' => true,
                        'current_image_value_id' => null,
                    ]),
                ],
            ],

            // Третий набор данных
            [
                [
                    'ID' => 15,
                    'DETAIL_PICTURE' => '',
                    'PROPERTY_LINKS_TO_IMAGES_VALUE' => ['http://project.local/image70.jpg'],
                    'PROPERTY_MORE_PHOTO_VALUE' => null,
                ],
                [],
                [
                    new RecordToProcess([
                        'element_id' => 15,
                        'address' => 'http://project.local/image70.jpg',
                        'is_main' => true,
                        'is_processed' => false,
                        'current_image_value_id' => null,
                        'temp_file_path' => '/tmp/file.jpg',
                    ]),
                ],
            ],

            // Четвертый набор данных
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
                [
                    [
                        'ID' => 1,
                        'FILE_ID' => 1,
                        'ELEMENT_ID' => 15,
                        'ADDRESS' =>'http://project.local/image1.jpg',
                        'IS_MAIN' => true,
                    ],
                ],
                [
                    new RecordToProcess([
                        'element_id' => 15,
                        'address' => 'http://project.local/image73.jpg',
                        'is_main' => true,
                        'is_processed' => false,
                        'current_image_value_id' => null,
                        'temp_file_path' => '/tmp/file.jpg',
                    ]),
                    new RecordToProcess([
                        'element_id' => 15,
                        'address' => 'http://project.local/image75.jpg',
                        'is_main' => false,
                        'is_processed' => false,
                        'current_image_value_id' => null,
                        'temp_file_path' => '/tmp/file.jpg',
                    ]),
                ],
            ],

            // Пятый набор данных
            [
                [
                    'ID' => 17,
                    'DETAIL_PICTURE' => '18',
                    'PROPERTY_LINKS_TO_IMAGES_VALUE' => [
                        'http://project.local/image1.jpg',
                        'http://project.local/image2.jpg',
                        'http://project.local/image3.jpg',
                    ],
                    'PROPERTY_MORE_PHOTO_VALUE' => [
                        '2' => '19',
                    ],
                ],
                [
                    [
                        'ID' => 1,
                        'FILE_ID' => 18,
                        'ELEMENT_ID' => 17,
                        'ADDRESS' =>'http://project.local/image1.jpg',
                        'IS_MAIN' => true,
                    ],
                    [
                        'ID' => 2,
                        'FILE_ID' => 19,
                        'ELEMENT_ID' => 17,
                        'ADDRESS' =>'http://project.local/image2.jpg',
                        'IS_MAIN' => false,
                    ],
                ],
                [
                    new RecordToProcess([
                        'element_id' => 17,
                        'address' => 'http://project.local/image1.jpg',
                        'is_main' => true,
                        'is_processed' => true,
                        'current_image_value_id' => null,
                    ]),
                    new RecordToProcess([
                        'element_id' => 17,
                        'address' => 'http://project.local/image2.jpg',
                        'is_main' => false,
                        'is_processed' => true,
                        'current_image_value_id' => 2,
                    ]),
                    new RecordToProcess([
                        'element_id' => 17,
                        'address' => 'http://project.local/image3.jpg',
                        'is_main' => false,
                        'is_processed' => false,
                        'current_image_value_id' => null,
                        'temp_file_path' => '/tmp/file.jpg',
                    ]),
                ],
            ],
        ];
    }
}
