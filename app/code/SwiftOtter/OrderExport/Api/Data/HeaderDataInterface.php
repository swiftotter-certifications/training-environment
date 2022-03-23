<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/23/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Api\Data;

interface HeaderDataInterface
{
    /**
     * @return string
     */
    public function getShipDate(): string;

    /**
     * @param string $shipDate
     * @return void
     */
    public function setShipDate(string $shipDate): void;

    /**
     * @return string
     */
    public function getMerchantNotes(): string;

    /**
     * @param string $merchantNotes
     * @return void
     */
    public function setMerchantNotes(string $merchantNotes): void;
}
