<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/24/21
 * @website https://swiftotter.com
 **/

namespace Project\Bug1NameNotSaving\Command;

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
    /** @var ApiClient */
    private $apiClient;
    private WriterInterface $configWriter;

    public function __construct(
        ApiClient $apiClient,
        WriterInterface $configWriter,
        string $name = null
    ) {
        parent::__construct($name);
        $this->apiClient = $apiClient;
        $this->configWriter = $configWriter;
    }

    protected function configure()
    {
        $this->setName('project:bug1:initialize');
        $this->setDescription('Updates the name of flashlight-1.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Beginning update for "flashlight-1".');

        $this->configWriter->save('general/single_store_mode/enabled', 0);

        $response = $this->apiClient->execute()
            ->put(Constants::REST_BASE . '/V1/products/flashlight-1', [
                'json' => ['product' => ['name' => 'LED High-Lumen Flashlight']]
            ]);

        $output->writeln('Update complete.');
    }
}
