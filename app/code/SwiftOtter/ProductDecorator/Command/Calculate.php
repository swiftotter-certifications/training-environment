<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Command;

use SwiftOtter\ProductDecorator\Action\UpdateDefaultPrice;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Calculate extends Command
{
    /** @var UpdateDefaultPrice */
    private $updateDefaultPrice;

    public function __construct(UpdateDefaultPrice $updateDefaultPrice, string $name = null)
    {
        parent::__construct($name);
        $this->updateDefaultPrice = $updateDefaultPrice;
    }

    protected function configure()
    {
        $this->setName('catalog:reindex:price');
        $this->setDescription('Reindexes the price for a product.');

        $this->addOption('sku', 'p', InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY, 'Product SKU');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $skus = $input->getOption('sku');
        $section = $output->section();
        $table = new Table($section);

        $table->setHeaders(['SKU', 'Time', 'Default Price']);
        $table->render();;

        foreach ($skus as $sku) {
            $start = microtime(true);
            $defaultPrice = $this->updateDefaultPrice->execute($sku);
            $table->appendRow([$sku, microtime(true) - $start, $defaultPrice]);
        }
    }
}
