<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/7/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Model;

trait StrictTypeTrait
{
    public function nullableInt($value): ?int
    {
        return $value ? (int)$value : null;
    }

    public function nullableFloat($value): ?float
    {
        return $value ? (float)$value : null;
    }
}
