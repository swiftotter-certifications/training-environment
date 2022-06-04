<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/2/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Model;

use Magento\Quote\Api\Data\CartInterface;
use SwiftOtter\Teaching\Api\ProcessorResponseInterface;
use SwiftOtter\Teaching\Api\ProcessorResponseInterfaceFactory;
use SwiftOtter\Teaching\Api\QuoteStageInterface;
use SwiftOtter\Teaching\Api\StageProcessorInterface;

class QuoteStage implements StageProcessorInterface, QuoteStageInterface
{
    private CartInterface $cart;
    private ProcessorResponseInterfaceFactory $responseFactory;

    public function __construct(
        CartInterface $cart,
        ProcessorResponseInterfaceFactory $responseFactory
    ) {
        $this->cart = $cart;
        $this->responseFactory = $responseFactory;
    }

    public function execute(): ProcessorResponseInterface
    {
        // do something
        return $this->responseFactory->create(['success' => true, 'details' => 'Nothing (quote)']);
    }
}
