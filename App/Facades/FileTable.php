<?php

namespace App\Facades;

use Bitrix\Main\FileTable as BaseFileTable;

class FileTable extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor()
    {
        return BaseFileTable::class;
    }
}
