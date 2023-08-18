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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;

class GetLocationPrintMethod extends Command
{
    /** @var LocationPrintMethodRepository */
    private $locationPrintMethodRepository;

    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    public function __construct(
        LocationPrintMethodRepository $locationPrintMethodRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        string $name = null
    ) {
        parent::__construct($name);
        $this->locationPrintMethodRepository = $locationPrintMethodRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    protected function configure()
    {
        $this->setName('teaching:load:sample');
        $this->setDescription('Loads sample values');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $section = $output->section();
        $table = new Table($section);

        $table->setHeaders(['ID', 'Location ID', 'SKU']);
        $table->render();

        $rows = $this->locationPrintMethodRepository->getList($this->searchCriteriaBuilder->create());
        foreach ($rows->getItems() as $row) {
            $table->appendRow([$row->getId(), $row->getLocationId(), $row->getSku()]);
        }

        return 0;
    }
}
