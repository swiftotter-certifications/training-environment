<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/22/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Palletizing\Plugin;

use SwiftOtter\Palletizing\Model\Pallet as PalletModel;
use SwiftOtter\Palletizing\Model\ResourceModel\Pallet as PalletResource;
use SwiftOtter\Palletizing\Model\ResourceModel\PalletQuoteItem as PalletQuoteItemResource;

class PersistQuoteItemAssociations
{
    /** @var PalletQuoteItemResource */
    private $palletQuoteItemResource;

    public function __construct(PalletQuoteItemResource $palletQuoteItemResource)
    {
        $this->palletQuoteItemResource = $palletQuoteItemResource;
    }

    public function afterSave(PalletResource $subject, PalletResource $resource, PalletModel $pallet): PalletResource
    {
        if (!$pallet->getExtensionAttributes()) {
            return $resource;
        }

        foreach ($pallet->getExtensionAttributes()->getPalletQuoteItems() as $quoteItem) {
            $this->palletQuoteItemResource->addQuoteItemToPallet((int)$pallet->getId(), (int)$quoteItem->getId());
        }

        return $resource;
    }
}