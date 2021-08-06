<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/24/21
 * @website https://swiftotter.com
 **/

namespace Project\Bug2CartDetailsThatDisappear\Command;

use GuzzleHttp\Client;
use Magento\Framework\App\Config\ScopeConfigInterface;
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

    public function __construct(
        ApiClient $apiClient,
        string $name = null
    ) {
        parent::__construct($name);
        $this->apiClient = $apiClient;
    }

    protected function configure()
    {
        $this->setName('project:bug2:initialize');
        $this->setDescription('Updates the name of flashlight-1.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Beginning update for "flashlight-1".');

        $response = $this->apiClient->execute()
            ->put(Constants::REST_BASE . '/V1/products/flashlight-1', [
                'json' => ['product' => ['name' => 'LED High-Lumen Flashlight']]
            ]);

        $output->writeln('Update complete.');
    }
}
