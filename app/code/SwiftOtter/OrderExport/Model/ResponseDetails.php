<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/6/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Model;

use SwiftOtter\OrderExport\Api\Data\ResponseInterface;

class ResponseDetails implements ResponseInterface
{
    /** @var bool */
    private $success;

    /** @var string */
    private $error;

    /**
     * @return bool
     */
    public function getSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     * @return string
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $error
     * @return string
     */
    public function setError(string $error): void
    {
        $this->error = $error;
    }
}