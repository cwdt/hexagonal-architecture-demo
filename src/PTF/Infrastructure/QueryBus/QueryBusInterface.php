<?php

namespace PTF\Infrastructure\QueryBus;

interface QueryBusInterface
{
    public function handle($query);
}