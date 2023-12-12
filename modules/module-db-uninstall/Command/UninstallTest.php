<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc.
 **/

namespace SwiftOtter\DbUninstall\Command;

use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UninstallTest extends Command
{
    private const NAME = 'name';

    protected function configure(): void
    {
        $this->setName('test:dbuninstall');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Nothing happened");

        return 0;
    }
}
