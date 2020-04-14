<?php

namespace App\Facades;

use CIBlockElement as BaseCIBlockElement;

class CIBlockElement extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor()
    {
        return BaseCIBlockElement::class;
    }
}
