<?php

namespace App\Modules;

use bitExpert\Disco\Annotations\Alias;
use bitExpert\Disco\Annotations\Bean;
use PTF\Application\ChangeStatus\ChangeStatusHandler;
use PTF\Application\ChangeStatus\ChangeStatusValidator;
use PTF\Application\GetState\GetStateHandler;

trait UsecaseConfiguration
{
    /**
     * @Bean({
     *     "aliases"={
     *          @Alias({"type" = true})
     *     }
     * })
     */
    public function getChangeStatusValidator(): ChangeStatusValidator
    {
        return new ChangeStatusValidator($this->getDaytimeRepository());
    }

    /**
     * @Bean({
     *     "aliases"={
     *          @Alias({"type" = true})
     *     }
     * })
     */
    public function getChangeStatusHandler(): ChangeStatusHandler
    {
        return new ChangeStatusHandler(
            $this->getStatusRepository()
        );
    }

    /**
     * @Bean({
     *     "aliases"={
     *          @Alias({"type" = true})
     *     }
     * })
     */
    public function getGetStateHandler(): GetStateHandler
    {
        return new GetStateHandler($this->getDaytimeRepository(), $this->getStatusRepository());
    }
}