<?php

namespace PTF\Infrastructure\CommandBus;

class Synchronous implements CommandBusInterface
{
    /** @var CommandBusInterface[] */
    private $chain;

    /**
     * @param CommandBusInterface[] $chain
     */
    public function __construct(array $chain)
    {
        $this->chain = $chain;
    }

    public function handle($command): void
    {
        foreach ($this->chain as $bus) {
            $bus->handle($command);
        }
    }
}