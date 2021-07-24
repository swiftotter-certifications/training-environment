<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/7/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Api\Data;

interface IncomingShareRequestInterface
{
    /**
     * @return bool
     */
    public function getEnabled(): bool;

    /**
     * @param bool|null $enabled
     */
    public function setEnabled(?bool $enabled): void;

    /**
     * @return ?string
     */
    public function getEmail(): ?string;

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void;

    /**
     * @return bool
     */
    public function getSend(): bool;

    /**
     * @param bool|null $share
     */
    public function setSend(?bool $share): void;
}
