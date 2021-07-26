<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/23/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use SwiftOtter\GiftCard\Attributes;
use SwiftOtter\GiftCard\Service\CurrentProduct;

class FrontendDetails implements ArgumentInterface
{
    /** @var CurrentProduct */
    private $currentProduct;

    public function __construct(CurrentProduct $currentProduct)
    {
        $this->currentProduct = $currentProduct;
    }

    public function getIsCustomAllowed(): bool
    {
        return $this->currentProduct->get()->getCustomAttribute(Attributes::IS_CUSTOM_ALLOWED)
            && (bool)$this->currentProduct->get()->getCustomAttribute(Attributes::IS_CUSTOM_ALLOWED)->getValue();
    }
}