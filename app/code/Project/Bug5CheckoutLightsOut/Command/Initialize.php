<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/24/21
 * @website https://swiftotter.com
 **/

namespace Project\Bug5CheckoutLightsOut\Command;

use Magento\Framework\App\Config\Storage\WriterInterface;
use Project\Bug6CheckoutDoesntRespond\Command\Initialize as Bug6Initialize;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Initialize extends Command
{
    public function __construct(
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('project:bug5:initialize');
        $this->setDescription('Initializes environment for bug 5');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        exec('git checkout bug5');
    }
}
