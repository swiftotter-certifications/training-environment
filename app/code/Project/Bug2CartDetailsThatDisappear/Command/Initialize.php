<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/24/21
 * @website https://swiftotter.com
 **/

namespace Project\Bug2CartDetailsThatDisappear\Command;

use GuzzleHttp\Client;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
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
        $this->setName('project:bug2:initialize');
        $this->setDescription('Updates the name of flashlight-1.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Beginning update for "flashlight-1".');

        $this->writer->save('general/single_store_mode/enabled', 0);

        $output->writeln('Update complete.');
    }
}
