<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/1/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Api;

interface ProcessorResponseInterface
{
    public function isSuccessful(): bool;

    public function getDetails(): ?string;
}
