<?php

namespace PTF\Infrastructure\Ui\Cli;

use DateTime;
use PTF\Application\GetState\GetStateQuery;
use PTF\Application\GetState\GetStateResponse;
use PTF\Infrastructure\QueryBus\QueryBus;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class GetStateCliHandler
{
    /** @var QueryBus */
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function handle(Args $args, IO $io): int
    {
        $query = new GetStateQuery(new DateTime($args->getArgument('datetime')));

        /** @var GetStateResponse $response */
        $response = $this->queryBus->handle($query);
        $io->writeLine($response->getState());

        return 0;
    }

}