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
use Magento\Framework\MessageQueue\PoisonPill\PoisonPillPutInterface;
use SwiftOtter\EventQueue\QueueEventManager;
use SwiftOtter\EventQueue\Action\TriggerEventDispatcher;
use SwiftOtter\EventQueue\Constants;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PoisonPill extends Command
{
    private PoisonPillPutInterface $poisonPillPut;

    public function __construct(
        PoisonPillPutInterface $poisonPillPut,
        string $name = null
    ) {
        parent::__construct($name);
        $this->poisonPillPut = $poisonPillPut;
    }

    protected function configure()
    {
        $this->setName('message:queue:poison');
        $this->setDescription('Poisons the event dispatcher');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->poisonPillPut->put();
    }
}
