<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/3/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Api;

use Magento\Sales\Api\Data\OrderInterface;

interface InternalPriceResponseInterface extends PriceResponseInterface
{
    /**
     * @return \Magento\Sales\Api\Data\OrderInterface|null
     */
    public function getOrder(): ?OrderInterface;
}
