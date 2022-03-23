<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/28/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Model;

use SwiftOtter\OrderExport\Api\Data\HeaderDataInterface;
use SwiftOtter\OrderExport\Api\Data\IncomingHeaderDataInterface;

class IncomingHeaderData implements IncomingHeaderDataInterface
{
    /** @var HeaderDataFactory */
    private $headerDataFactory;

    /** @var string */
    private $shipDate;

    /** @var string */
    private $merchantNotes;

    public function __construct(\SwiftOtter\OrderExport\Api\Data\HeaderDataInterfaceFactory $headerDataFactory)
    {
        $this->headerDataFactory = $headerDataFactory;
    }

    /**
     * @return string
     */
    public function getShipDate(): string
    {
        return $this->shipDate;
    }

    /**
     * @param string $shipDate
     */
    public function setShipDate(string $shipDate): void
    {
        $this->shipDate = $shipDate;
    }

    /**
     * @return string
     */
    public function getMerchantNotes(): string
    {
        return (string)$this->merchantNotes;
    }

    /**
     * @param string $merchantNotes
     */
    public function setMerchantNotes(string $merchantNotes): void
    {
        $this->merchantNotes = $merchantNotes;
    }

    public function getHeaderData(): HeaderDataInterface
    {
        $headerData = $this->headerDataFactory->create();
        $headerData->setMerchantNotes($this->getMerchantNotes());
        $headerData->setShipDate($this->getShipDate());

        return $headerData;
    }
}
