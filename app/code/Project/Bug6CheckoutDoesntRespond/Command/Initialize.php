<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/24/21
 * @website https://swiftotter.com
 **/

namespace Project\Bug6CheckoutDoesntRespond\Command;

use GuzzleHttp\Client;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Project\Bug6CheckoutDoesntRespond\Plugin\PreventMethodOnUnified;
use Project\Common\Action\ApiClient;
use Project\Common\Action\CreateApiToken;
use Project\Common\Constants;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Initialize extends Command
{
    private WriterInterface $writer;

    public function __construct(
        WriterInterface $writer,
        string $name = null
    ) {
        parent::__construct($name);
        $this->writer = $writer;
    }

    protected function configure()
    {
        $this->setName('project:bug6:initialize');
        $this->setDescription('Initializes environment for bug 6');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Enabling scenario for bug 6. Get ready!');

        $this->writer->save(PreventMethodOnUnified::ENABLED, 1);

        $output->writeln('Update complete.');
    }
}
