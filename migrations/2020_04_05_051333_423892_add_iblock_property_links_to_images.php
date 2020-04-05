<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddIblockPropertyLinksToImages20200405051333423892 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws MigrationException
     */
    public function up()
    {
        $iblockId = $this->getIblockIdByCode('clothes');

        $propId = $this->addIblockElementProperty([
            'NAME' => 'Ссылки на изображения',
            'SORT' => 500,
            'CODE' => 'LINKS_TO_IMAGES',
            'PROPERTY_TYPE' => 'S', // Список
            'MULTIPLE'  => 'Y',
            'IS_REQUIRED' => 'N',
            'IBLOCK_ID' => $iblockId
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     */
    public function down()
    {
        $iblockId = $this->getIblockIdByCode('clothes');

        $this->deleteIblockElementPropertyByCode($iblockId, 'LINKS_TO_IMAGES');
    }
}
