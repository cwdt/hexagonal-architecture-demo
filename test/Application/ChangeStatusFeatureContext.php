<?php

namespace Tests\Application;

use Behat\Behat\Context\Context;
use DateTime;
use PTF\Application\ChangeStatus\ChangeStatusCommand;
use PTF\Application\ChangeStatus\ChangeStatusHandler;
use PTF\Application\ChangeStatus\ChangeStatusValidator;
use PTF\Application\GetState\GetStateHandler;
use PTF\Application\GetState\GetStateQuery;
use PTF\Application\GetState\GetStateResponse;
use Ptf\Application\ValidationException;
use PTF\Infrastructure\Persistence\InMemory\StatusRepository;
use PTF\Infrastructure\Persistence\Mock\DaytimeRepository;
use RuntimeException;

class ChangeStatusFeatureContext implements Context
{
    /** @var ChangeStatusHandler */
    private $changeStatusHandler;

    /** @var ChangeStatusValidator */
    private $changeStatusValidator;

    /** @var StatusRepository */
    private $statusRepository;

    /** @var DaytimeRepository */
    private $dayTimeRepository;

    /** @var GetStateHandler */
    private $getStateHandler;

    /** @var array */
    private $errors;

    /**
     * @setup
     */
    public function __construct()
    {
        $this->statusRepository = new StatusRepository();
        $this->dayTimeRepository = new DaytimeRepository();
        $this->changeStatusHandler = new ChangeStatusHandler($this->statusRepository);
        $this->changeStatusValidator = new ChangeStatusValidator($this->dayTimeRepository);
        $this->getStateHandler =  new GetStateHandler($this->dayTimeRepository, $this->statusRepository);
    }

    /**
     * @Given the sunrise is at :startHour and the sunset is at :endHour
     */
    public function givenTheSunriseIsAtAndTheSunsetIsAt(string $startHour, string $endHour)
    {
        $this->dayTimeRepository->setDaytime($startHour, $endHour);
    }

    /**
     * @When I change the status to :state from :from until :until
     */
    public function iChangeTheStatusToFromUntil(string $state, string $from, string $until): void
    {
        $command = new ChangeStatusCommand(
            uniqid(),
            $state,
            new DateTime($from),
            new DateTime($until)
        );

        try {
            $this->changeStatusValidator->validate($command);
            $this->changeStatusHandler->handle($command);
        } catch (ValidationException $e) {
            $this->errors = $e->getErrors();
        }
    }

    /**
     * @Then the state at :dateTime will be :state
     */
    public function theStateAtWillBe(string $dateTime, string $state)
    {
        $query = new GetStateQuery(new DateTime($dateTime));

        /** @var GetStateResponse $response */
        $response = $this->getStateHandler->handle($query);

        if ($response->getState() !== $state) {
            throw new RuntimeException('Expected state ' . $state . ' got ' . $response->getState());
        }
    }

    /**
     * @Then the validation for the field :field will fail with the message :message
     */
    public function theValidationForTheFieldWillFailWithTheMessage(string $field, string $message)
    {
        if (isset($this->errors[$field])) {
            foreach($this->errors[$field] as $actualMessage) {
                if ($message === $actualMessage) {
                    return;
                }
            }
        }

        throw new RuntimeException('There is not validation error for field ' . $field . ' stating ' . $message);
    }
}