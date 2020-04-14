<?php

namespace App\Traits;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

trait LoadsModules
{
    /**
     * loadModules.
     *
     * @access	protected
     * @return	void
     */
    protected function loadModules(): void
    {
        if (! property_exists($this, 'modules') || ! is_array($this->modules)) {
            return;
        }

        foreach ($this->modules as $moduleId) {
            if (! Loader::includeModule($moduleId)) {
                throw new LoaderException('Не удалось загрузить модуль ' . $moduleId);
            }
        }
    }
}
