<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc., 2019/09/28
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Api\Data;

interface ExternalPaymentVaultTokenInterface
{
    /**
     * @return string
     */
    public function getPublicHash(): string;

    /**
     * @return string
     */
    public function getPaymentMethodCode(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getLast4(): string;

    /**
     * @return string
     */
    public function getExpirationDate(): string;
}