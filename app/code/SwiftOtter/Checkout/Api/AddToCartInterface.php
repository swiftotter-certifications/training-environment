<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/26/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Api;

use SwiftOtter\Checkout\Api\Data\AddToCartDetailsInterface;

interface AddToCartInterface
{
    /**
     * @param \SwiftOtter\Checkout\Api\Data\AddToCartDetailsInterface[] $details
     * @param string $countryId
     * @return \SwiftOtter\DownloadProduct\Api\Data\ProductDetailInterface[]
     */
    public function execute(array $details, string $countryId): array;
}
