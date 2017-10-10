<?php

namespace Tests\Application;

use Interop\Container\ContainerInterface;

class ContainerFactory
{
    protected static $container;

    public static function create(): ContainerInterface
    {
        if (! self::$container) {
            self::$container = require(__DIR__ . '/../../app/container.php');
        }

        return self::$container;
    }
}