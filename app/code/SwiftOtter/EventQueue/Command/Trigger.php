<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/14/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\EventQueue\Command;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use SwiftOtter\EventQueue\QueueEventManager;
use SwiftOtter\EventQueue\Action\TriggerEventDispatcher;
use SwiftOtter\EventQueue\Constants;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Trigger extends Command
{
    const TRIGGER = 'trigger';

    /** @var State */
    private $state;

    /** @var TriggerEventDispatcher */
    private $actionIn;

    /** @var QueueEventManager */
    private $queueIn;

    public function __construct(
        TriggerEventDispatcher $actionIn,
        QueueEventManager $queueIn,
        State $state,
        string $name = null
    ) {
        parent::__construct($name);
        $this->state = $state;
        $this->actionIn = $actionIn;
        $this->queueIn = $queueIn;
    }

    protected function configure()
    {
        $this->setName('message:queue:trigger');
        $this->setDescription('Triggers event dispatcher');
        $this->addOption(
            Constants::EVENT_NAME,
            null,
            InputOption::VALUE_REQUIRED,
            'Event Name'
        );

        $this->addOption(
            Constants::DATA,
            null,
            InputOption::VALUE_OPTIONAL,
            'JSON input of data'
        );

        $this->addOption(
            self::TRIGGER,
            null,
            InputOption::VALUE_OPTIONAL,
            'Trigger RabbitMQ. Otherwise, goes directly to event processor'
        );

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->setAreaCode(Area::AREA_FRONTEND);
        } catch (LocalizedException $ex) {
        }

        $data = rtrim($input->getOption(Constants::DATA), "'");
        $data = ltrim($data, "'");

        if (!$input->getOption(self::TRIGGER)) {
            $this->actionIn->process(
                $input->getOption(Constants::EVENT_NAME),
                json_decode($data, true)
            );
        } else {
            $this->queueIn->dispatch(
                $input->getOption(Constants::EVENT_NAME),
                json_decode($data, true)
            );
        }
    }
}
