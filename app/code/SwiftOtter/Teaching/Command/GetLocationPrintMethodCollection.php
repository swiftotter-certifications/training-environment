<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Command;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use SwiftOtter\ProductDecorator\Model\LocationPrintMethod;
use SwiftOtter\ProductDecorator\Model\LocationPrintMethodRepository;
use SwiftOtter\ProductDecorator\Model\ResourceModel\LocationPrintMethod as LocationPrintMethodResourceModel;
use SwiftOtter\ProductDecorator\Model\ResourceModel\LocationPrintMethod\CollectionFactory as LocationPrintMethodCollectionFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;

class GetLocationPrintMethodCollection extends Command
{
    /** @var LocationPrintMethodCollectionFactory */
    private $locationPrintMethodCollectionFactory;

    public function __construct(
        LocationPrintMethodCollectionFactory $locationPrintMethodCollectionFactory,
        string                               $name = null
    ) {
        parent::__construct($name);
        $this->locationPrintMethodCollectionFactory = $locationPrintMethodCollectionFactory;
    }

    protected function configure()
    {
        $this->setName('teaching:load:sample:collection');
        $this->setDescription('Loads collection');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $section = $output->section();
        $table = new Table($section);

        $table->setHeaders(['SKU', 'Location ID']);
        $table->render();;

        $rows = $this->locationPrintMethodCollectionFactory->create();

        /** @var LocationPrintMethod $row */
        foreach ($rows as $row) {
            $table->appendRow([$row->getSku(), $row->getLocationId()]);
        }
    }
}
