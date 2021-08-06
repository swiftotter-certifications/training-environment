<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/22/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\Plugin;

use SwiftOtter\HandlingFee\Model\HandlingFee as HandlingFeeModel;
use SwiftOtter\HandlingFee\Model\ResourceModel\HandlingFee as HandlingFeeResource;
use SwiftOtter\HandlingFee\Model\ResourceModel\HandlingFeeQuoteItem as HandlingFeeQuoteItemResource;

class PersistQuoteItemAssociations
{
    /** @var HandlingFeeQuoteItemResource */
    private $handlingFeeQuoteItemResource;

    public function __construct(HandlingFeeQuoteItemResource $handlingFeeQuoteItemResource)
    {
        $this->handlingFeeQuoteItemResource = $handlingFeeQuoteItemResource;
    }

    public function afterSave(
        HandlingFeeResource $subject,
        HandlingFeeResource $resource,
        HandlingFeeModel $handlingFee
    ): HandlingFeeResource {
        if (!$handlingFee->getExtensionAttributes()) {
            return $resource;
        }

        foreach ($handlingFee->getExtensionAttributes()->getHandlingFeeQuoteItems() as $quoteItem) {
            $this->handlingFeeQuoteItemResource->addQuoteItemToHandlingFee((int)$handlingFee->getId(), (int)$quoteItem->getId());
        }

        return $resource;
    }
}
