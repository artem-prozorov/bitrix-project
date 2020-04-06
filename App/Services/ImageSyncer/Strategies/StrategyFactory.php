<?php

namespace App\Services\ImageSyncer\Strategies;

class StrategyFactory
{
    protected static $mainImageStrategy;

    protected static $additionalImageStrategy;

    /**
     * Возвращает подходящую стратегию
     *
     * @access	public static
     * @param	int	$key	
     * @return	StrategyInterface
     */
    public static function getStrategy(int $key): StrategyInterface
    {
        if ($key === 0) {
            if (empty(static::$mainImageStrategy)) {
                static::$mainImageStrategy = new MainImageStrategy();
            }

            return static::$mainImageStrategy;
        }

        if (empty(static::$additionalImageStrategy)) {
            static::$additionalImageStrategy = new AdditionalImageStrategy();
        }

        return static::$additionalImageStrategy;
    }
}
