<?php

namespace PTF\Infrastructure\QueryBus;

use Interop\Container\ContainerInterface;

class QueryBus implements QueryBusInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function handle($query)
    {
        $handlerClass = str_replace('Query', 'Handler', get_class($query));
        $handler = $this->container->get($handlerClass);

        return $handler->handle($query);
    }
}
