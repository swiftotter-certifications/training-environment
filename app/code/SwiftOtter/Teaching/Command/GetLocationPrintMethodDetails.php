<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Command;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use SwiftOtter\ProductDecorator\Model\LocationPrintMethodRepository;
use SwiftOtter\ProductDecorator\Model\ResourceModel\LocationPrintMethod as LocationPrintMethodResourceModel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;

class GetLocationPrintMethodDetails extends Command
{
    /** @var LocationPrintMethodResourceModel */
    private $locationPrintMethodResource;

    public function __construct(
        LocationPrintMethodResourceModel $locationPrintMethodResource,
        string                           $name = null
    ) {
        parent::__construct($name);
        $this->locationPrintMethodResource = $locationPrintMethodResource;
    }

    protected function configure()
    {
        $this->setName('teaching:load:sample:details');
        $this->setDescription('Loads sample values');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $section = $output->section();
        $table = new Table($section);

        $table->setHeaders(['ID', 'Value']);
        $table->render();

        $rows = $this->locationPrintMethodResource->getPrintMethodIdsGroupedByLocationIds('great-tent-1');
        foreach ($rows as $id => $value) {
            $table->appendRow([$id, implode(', ', $value)]);
        }

        return 0;
    }
}
