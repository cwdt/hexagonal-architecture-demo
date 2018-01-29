<?php

namespace App\Modules;

use bitExpert\Disco\Annotations\Alias;
use bitExpert\Disco\Annotations\Bean;
use PTF\Infrastructure\Ui\Cli\ChangeStatusCliHandler;
use PTF\Infrastructure\Ui\Cli\GetStateCliHandler;

trait CliConfiguration
{
    /**
     * @Bean({
     *     "aliases"={
     *          @Alias({"type" = true})
     *     }
     * })
     */
    public function getChangeStatusCLIHandler(): ChangeStatusCliHandler
    {
        return new ChangeStatusCliHandler(
            $this->getCommandBus()
        );
    }

    /**
     * @Bean({
     *     "aliases"={
     *          @Alias({"type" = true})
     *     }
     * })
     */
    public function getGetStateCLIHandler(): GetStateCliHandler
    {
        return new GetStateCliHandler($this->getQueryBus());
    }
}