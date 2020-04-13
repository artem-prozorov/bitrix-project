<?php

namespace App\Facades;

use App\Base\FileManager as FileManagerService;

class FileManager extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor()
    {
        return FileManagerService::class;
    }
}
