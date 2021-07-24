<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/26/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Api\Data;

interface CountryResponseInterface
{
    /**
     * @return string
     */
    public function getCountryCode(): string;

    /**
     * @return string
     */
    public function getCountryName(): string;

    /**
     * @return bool
     */
    public function getShowCompanySelector(): bool;
}
