<?php

namespace PTF\Infrastructure\CommandBus\Chain;

use Psr\Container\ContainerInterface;
use PTF\Infrastructure\CommandBus\CommandBusInterface;

class Handle implements CommandBusInterface
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

    public function handle($command): void
    {
        $handlerClass = str_replace('Command', 'Handler', get_class($command));
        $handler = $this->container->get($handlerClass);

        $handler->handle($command);
    }
}