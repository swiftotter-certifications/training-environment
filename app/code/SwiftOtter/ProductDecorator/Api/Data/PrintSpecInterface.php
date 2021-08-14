<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface PrintSpecInterface extends ExtensibleDataInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return string|null
     */
    public function getClientId(): ?string;

    /**
     * @return bool
     */
    public function getIsDeleted(): bool;

    /**
     * @param $id
     * @return void
     */
    public function setId($id): void;

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void;

    /**
     * @param string|null $value
     */
    public function setClientId(?string $value): void;

    /**
     * @param bool $value
     */
    public function setIsDeleted(bool $value): void;

    /**
     * @return \SwiftOtter\ProductDecorator\Api\Data\PrintSpecExtensionInterface
     */
    public function getExtensionAttributes(): PrintSpecExtensionInterface;

    /**
     * @param \SwiftOtter\ProductDecorator\Api\Data\PrintSpecExtensionInterface $attributes
     * @return void
     */
    public function setExtensionAttributes(PrintSpecExtensionInterface $attributes): void;
}
