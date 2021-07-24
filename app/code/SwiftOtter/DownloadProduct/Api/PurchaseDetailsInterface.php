<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/13/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Api;

interface PurchaseDetailsInterface
{
    /**
     * @return bool
     */
    public function getSuccess(): bool;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return float
     */
    public function getRawTotal(): float;

    /**
     * @return string
     */
    public function getTotal(): string;

    /**
     * @return string
     */
    public function getOrderNumber(): string;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return string
     */
    public function getPurchaseType(): string;

    /**
     * @return string
     */
    public function getTestId(): string;

    /**
     * @return string
     */
    public function getVideo(): string;
}