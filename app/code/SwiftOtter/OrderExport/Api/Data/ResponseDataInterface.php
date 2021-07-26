<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/9/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Api\Data;

interface ResponseDataInterface
{
    /**
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * @param bool $success
     * @return void
     */
    public function setSuccess(bool $success): void;

    /**
     * @return string
     */
    public function getError(): string;

    /**
     * @param string $error
     * @return void
     */
    public function setError(string $error): void;
}