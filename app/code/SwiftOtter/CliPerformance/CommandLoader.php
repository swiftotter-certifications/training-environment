<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/2/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\CliPerformance;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Console\CommandList\Proxy as CommandList;
use Magento\Framework\Console\CommandListInterface;
use Magento\Framework\ObjectManager\ConfigInterface as Config;

class CommandLoader implements CommandListInterface
{
    /** @var Config */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getCommands()
    {
        $parameters = $this->config->getArguments(\Magento\Framework\Console\CommandList::class);
        if (!isset($parameters['commands'])) {
            return [];
        }

        $commands = [];
        foreach ($parameters['commands'] as $name => $details) {
            $commands[] = InitializeCommand::execute($details['instance']);
        }

        return array_filter($commands);
    }
}
