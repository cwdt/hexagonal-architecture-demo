<?php

namespace PTF\Infrastructure\CommandBus;

interface CommandBusInterface
{
    public function handle($command): void;
}