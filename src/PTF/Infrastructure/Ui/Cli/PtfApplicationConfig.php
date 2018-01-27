<?php

namespace PTF\Infrastructure\Ui\Cli;

use Interop\Container\ContainerInterface;
use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Config\DefaultApplicationConfig;

class PtfApplicationConfig extends DefaultApplicationConfig
{
    /** @var ContainerInterface */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct(null, null);

        $this->container = $container;
    }

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('ptf')
            ->setVersion('0.0.1')
            ->beginCommand('change-status')
                ->setDescription('Change status')
                ->addArgument('state', Argument::REQUIRED, 'State')
                ->addArgument('from', Argument::REQUIRED, 'From (supported DateTime format)')
                ->addArgument('until', Argument::REQUIRED, 'Until (supported DateTime format)')
                ->setHandler(function() {
                    return $this->container->get(ChangeStatusCliHandler::class);
                })
            ->end()
            ->beginCommand('get-state')
                ->setDescription('Get state')
                ->addArgument('datetime', Argument::REQUIRED, 'yyyy-mm-dd')
                ->setHandler(function() {
                    return $this->container->get(GetStateCliHandler::class);
                })
            ->end();
    }
}
