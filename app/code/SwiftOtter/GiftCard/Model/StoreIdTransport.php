<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/6/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Model;

use Magento\Store\Model\Store;

class StoreIdTransport
{
    /** @var int */
    private $storeId;

    /**
     * @return int
     */
    public function getStoreId(): int
    {
        return $this->storeId ?? Store::DEFAULT_STORE_ID;
    }

    /**
     * @param int $storeId
     */
    public function setStoreId(int $storeId): void
    {
        $this->storeId = $storeId;
    }
}