<?php

namespace PTF\Infrastructure\Ui\Cli;

use DateTime;
use PTF\Application\ChangeStatus\ChangeStatusCommand;
use PTF\Application\ValidationException;
use PTF\Infrastructure\CommandBus\CommandBusInterface;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class ChangeStatusCliHandler
{
    /** @var CommandBusInterface */
    private $commandBus;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle(Args $args, IO $io): int
    {
        $command = new ChangeStatusCommand(
            uniqid(),
            $args->getArgument('state'),
            new DateTime($args->getArgument('from')),
            new DateTime($args->getArgument('until'))
        );

        try {
            $this->commandBus->handle($command);
        } catch (ValidationException $e) {
            foreach ($e->getErrors() as $field => $fieldErrors) {
                foreach ($fieldErrors as $error) {
                    $io->errorLine('<error>' . $field . ': ' . $error . '</error>');
                }
            }

            return 1;

        }

        $io->writeLine('Changed status successfully');

        return 0;
    }

}