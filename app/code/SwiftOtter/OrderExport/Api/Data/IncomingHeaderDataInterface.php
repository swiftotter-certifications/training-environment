<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/6/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Api\Data;

use SwiftOtter\OrderExport\Model\HeaderData;

interface IncomingHeaderDataInterface
{
    /**
     * @return string
     */
    public function getShipDate(): string;

    /**
     * @param string $shipDate
     */
    public function setShipDate(string $shipDate): void;

    /**
     * @return string
     */
    public function getMerchantNotes(): string;

    /**
     * @param string $notes
     */
    public function setMerchantNotes(string $notes): void;

    /**
     * @return \SwiftOtter\OrderExport\Model\HeaderData
     */
    public function getHeaderData(): HeaderData;
}