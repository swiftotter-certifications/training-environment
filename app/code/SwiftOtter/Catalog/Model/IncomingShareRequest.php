<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/7/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Model;

use SwiftOtter\Catalog\Api\Data\IncomingShareRequestInterface;

class IncomingShareRequest implements IncomingShareRequestInterface
{
    /** @var bool */
    private $enabled;

    /** @var bool */
    private $share;

    /** @var string */
    private $email;

    /**
     * @return bool
     */
    public function getEnabled(): bool
    {
        return (bool)$this->enabled;
    }

    /**
     * @param bool|null $enabled
     */
    public function setEnabled(?bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return bool
     */
    public function getSend(): bool
    {
        return (bool)$this->share;
    }

    /**
     * @param bool|null $share
     */
    public function setSend(?bool $share): void
    {
        $this->share = $share;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return (string)$this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

}
