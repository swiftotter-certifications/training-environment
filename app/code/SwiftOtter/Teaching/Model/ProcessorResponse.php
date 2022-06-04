<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/2/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Model;

use SwiftOtter\Teaching\Api\ProcessorResponseInterface;

class ProcessorResponse implements ProcessorResponseInterface
{
    private bool $success;
    private ?string $details;

    public function __construct(bool $success, ?string $details)
    {
        $this->success = $success;
        $this->details = $details;
    }

    public function isSuccessful(): bool
    {
        return $this->success;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }


}
