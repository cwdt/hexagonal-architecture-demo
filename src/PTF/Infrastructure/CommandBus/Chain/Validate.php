<?php

namespace PTF\Infrastructure\CommandBus\Chain;

use Psr\Container\ContainerInterface;
use PTF\Infrastructure\CommandBus\CommandBusInterface;

class Validate implements CommandBusInterface
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
        $validationClass = str_replace('Command', 'Validator', get_class($command));
        $validator = $this->container->get($validationClass);

        $validator->validate($command);
    }
}