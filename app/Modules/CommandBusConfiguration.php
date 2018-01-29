<?php

namespace App\Modules;

use bitExpert\Disco\Annotations\Bean;
use bitExpert\Disco\BeanFactoryRegistry;
use PTF\Infrastructure\CommandBus\Chain\Handle;
use PTF\Infrastructure\CommandBus\Chain\Validate;
use PTF\Infrastructure\CommandBus\Synchronous;
use PTF\Infrastructure\QueryBus\QueryBus;

trait CommandBusConfiguration
{
    /**
     * @Bean()
     */
    public function getCommandBus(): Synchronous
    {
        return new Synchronous(
            [
                $this->getValidate(),
                $this->getHandle(),
            ]
        );
    }

    /**
     * @Bean()
     */
    public function getQueryBus(): QueryBus
    {
        return new QueryBus(BeanFactoryRegistry::getInstance());
    }

    /**
     * @Bean()
     */
    protected function getValidate(): Validate
    {
        return new Validate(BeanFactoryRegistry::getInstance());
    }

    /**
     * @Bean()
     */
    protected function getHandle(): Handle
    {
        return new Handle(BeanFactoryRegistry::getInstance());
    }
}