<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/22/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Model;

use SwiftOtter\Catalog\Api\Data\IncomingOrderDetailsInterface;

class IncomingOrderTransport
{
    /** @var IncomingOrderDetailsInterface */
    private $details;

    public function get(): ?IncomingOrderDetailsInterface
    {
        return $this->details;
    }

    public function set(IncomingOrderDetailsInterface $details)
    {
        $this->details = $details;
    }
}