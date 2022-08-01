<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/19/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Service;

class CountryProvider
{
    /**
     * This should ultimately be tied into store configuration at the website scope.
     *
     * @return string[]
     */
    public function get(): array
    {
        return ['US', 'CA'];
    }
}
