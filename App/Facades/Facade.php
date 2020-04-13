<?php

namespace APp\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;

abstract class Facade extends BaseFacade
{
    /**
     * @inheritDoc
     */
    protected static function resolveFacadeInstance($name)
    {
        if (is_object($name)) {
            return $name;
        }

        if (isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }

        $class = static::getFacadeAccessor();

        return static::$resolvedInstance[$name] = new $class;
    }
}
