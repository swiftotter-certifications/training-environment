<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/24/21
 * @website https://swiftotter.com
 **/

namespace Project\Bug5CheckoutLightsOut\Command;

use GuzzleHttp\Client;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Project\Bug6CheckoutDoesntRespond\Command\Initialize as Bug6Initialize;
use Project\Bug6CheckoutDoesntRespond\Command\Revert as Bug6Revert;
use Project\Bug6CheckoutDoesntRespond\Plugin\PreventMethodOnUnified;
use Project\Common\Action\ApiClient;
use Project\Common\Action\CreateApiToken;
use Project\Common\Constants;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Revert extends Command
{
    public function __construct(
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('project:bug5:revert');
        $this->setDescription('Resets bug 5 so you can use the store again.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
