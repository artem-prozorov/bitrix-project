<?php
namespace App\Datamanagers;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use App\Models\ImageToLink;
use App\Collections\ImagesToLinksCollection;

/**
 * Class LinksTable
 * 
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> FILE_ID int mandatory
 * <li> ELEMENT_ID int mandatory
 * <li> ADDRESS string(255) optional
 * <li> IS_MAIN int mandatory
 * </ul>
 *
 * @package Bitrix\To
 **/

class ImagesToLinksTable extends Main\Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'images_to_links';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			'ID' => [
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
				'title' => "Идентификатор",
            ],
			'FILE_ID' => [
				'data_type' => 'integer',
				'required' => true,
				'title' => "Идентификатор файла",
            ],
			'ELEMENT_ID' => [
				'data_type' => 'integer',
				'required' => true,
				'title' => "Идентификатор элемента инфоблока",
            ],
			'ADDRESS' => [
				'data_type' => 'string',
				'validation' => [__CLASS__, 'validateAddress'],
				'title' => "Адрес изображения",
            ],
			'IS_MAIN' => [
				'data_type' => 'integer',
				'required' => true,
				'title' => "Является ли главным",
            ],
        ];
	}
	/**
	 * Returns validators for ADDRESS field.
	 *
	 * @return array
	 */
	public static function validateAddress()
	{
		return array(
			new Main\Entity\Validator\Length(null, 255),
		);
	}

	public static function getObjectClass()
    {
        return ImageToLink::class;
	}
	
	public static function getCollectionClass()
    {
        return ImagesToLinksCollection::class;
    }
}