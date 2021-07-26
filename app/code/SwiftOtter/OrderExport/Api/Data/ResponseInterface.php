<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/6/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Api\Data;

interface ResponseInterface
{
    /**
     * @return bool
     */
    public function getSuccess(): bool;

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