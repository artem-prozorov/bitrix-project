<?php

use Mockery\Adapter\Phpunit\MockeryTestCase;
use App\Services\ImageSyncer\Repository;
use App\Services\ImageSyncer\DataObjects\RecordToProcess;
use App\Facades\FileTable;
use Bitrix\Main\ORM\Query\Result;
use App\Collections\ImagesToLinksCollection;
use App\Services\ImageSyncer\Collections\RecordsToProcessCollection;
use App\Models\ImageToLink;

class FileSynchronizationTest extends MockeryTestCase
{
    /**
     * @dataProvider getData
     */
    public function testFileSynchronization(array $fetchAllResult, $records, $expectedData)
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAll')->willReturn($fetchAllResult);

        FileTable::shouldReceive('getList')->andReturn($result);

        $repository = new Repository();

        $imagesToLinks = $repository->getSyncedImagesToLinks($records);

        $this->assertEquals(2, count($imagesToLinks));

        $key = 0;
        foreach ($imagesToLinks as $imageToLink) {
            $this->assertEquals($expectedData[$key]['ELEMENT_ID'], $imageToLink->getElementId());
            $this->assertEquals($expectedData[$key]['ADDRESS'], $imageToLink->getAddress());
            $this->assertEquals($expectedData[$key]['FILE_ID'], $imageToLink->getFileId());
            $this->assertEquals($expectedData[$key]['IS_MAIN'], $imageToLink->getIsMain());

            $key++;
        }
    }

    /**
     * getData.
     *
     * @access	public
     * @return	array
     */
    public function getData(): array
    {
        return [
            [
                [
                    ['ID' => 1, 'ORIGINAL_NAME' => 'temp_image1.jpg'],
                    ['ID' => 2, 'ORIGINAL_NAME' => 'temp_image2.jpg'],
                ],
                new RecordsToProcessCollection([
                    new RecordToProcess([
                        'element_id' => 50,
                        'address' => 'http://project.local/image7.jpg',
                        'is_main' => true,
                        'is_processed' => true,
                        'current_image_value_id' => null,
                    ]),
                    new RecordToProcess([
                        'element_id' => 50,
                        'address' => 'http://project.local/image1.jpg',
                        'is_main' => false,
                        'is_processed' => false,
                        'current_image_value_id' => 26,
                        'temp_file_path' => '/tmp/temp_image1.jpg',
                    ]),
                    new RecordToProcess([
                        'element_id' => 50,
                        'address' => 'http://project.local/image2.jpg',
                        'is_main' => false,
                        'is_processed' => false,
                        'current_image_value_id' => null,
                        'temp_file_path' => '/tmp/temp_image2.jpg',
                    ]),
                ]),
                [
                    [
                        'ELEMENT_ID' => 50,
                        'ADDRESS' => 'http://project.local/image1.jpg',
                        'FILE_ID' => 1,
                        'IS_MAIN' => 0,
                    ],
                    [
                        'ELEMENT_ID' => 50,
                        'ADDRESS' => 'http://project.local/image2.jpg',
                        'FILE_ID' => 2,
                        'IS_MAIN' => 0,
                    ],
                ],
            ],
        ];
    }
}
