<?php

use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function testApplicationBootstraps()
    {
        $class = '\Bitrix\Main\Application';

        $this->assertTrue(class_exists($class));
    }
}
