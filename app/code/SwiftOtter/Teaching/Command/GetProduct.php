<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Command;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use SwiftOtter\Repository\Api\FastProductRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;

class GetProduct extends Command
{
    private ProductRepositoryInterface $productRepository;
    private FastProductRepositoryInterface $fastProductRepository;
    private ScopeConfigInterface $scopeConfig;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        FastProductRepositoryInterface $fastProductRepository,
        ScopeConfigInterface $scopeConfig,
        string $name = null
    ) {
        parent::__construct($name);
        $this->productRepository = $productRepository;
        $this->fastProductRepository = $fastProductRepository;
        $this->scopeConfig = $scopeConfig;
    }

    protected function configure()
    {
        $this->setName('teaching:load:product');
        $this->setDescription('Loads a product by SKU');

        $this->addOption('sku', 'p', InputOption::VALUE_REQUIRED, 'Product SKU');
        $this->addOption('fast', 'f', InputOption::VALUE_REQUIRED, 'Use Fast');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sku = $input->getOption('sku');
        $section = $output->section();
        $table = new Table($section);

        $table->setHeaders(['SKU', 'Name', 'Time To Load']);
        $table->render();;

        $time = microtime(true);
        if (!$input->getOption('fast')) {
            $product = $this->productRepository->get($sku);
        } else {
            $product = $this->fastProductRepository->get($sku, false, false, false, ['name']);
        }

        $table->appendRow([$sku, $product->getName(), microtime(true) - $time]);

        return 0;
    }
}
