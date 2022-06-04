<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/2/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Model;

use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\OrderInterface;
use SwiftOtter\Teaching\Api\InvoiceStageInterface;
use SwiftOtter\Teaching\Api\ProcessorResponseInterface;
use SwiftOtter\Teaching\Api\ProcessorResponseInterfaceFactory;
use SwiftOtter\Teaching\Api\StageProcessorInterface;

class InvoiceStage implements StageProcessorInterface, InvoiceStageInterface
{
    private InvoiceInterface $invoice;
    private ProcessorResponseInterfaceFactory $responseFactory;

    public function __construct(
        InvoiceInterface $invoice,
        ProcessorResponseInterfaceFactory $responseFactory
    ) {
        $this->invoice = $invoice;
        $this->responseFactory = $responseFactory;
    }

    public function execute(): ProcessorResponseInterface
    {
        // do something
        return $this->responseFactory->create(['success' => true, 'details' => 'Nothing (invoice)']);
    }
}
