<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Api\Data;

interface UnifiedSaleInterface
{
    const TYPE_QUOTE = 'quote';

    const TYPE_ORDER = 'order';

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return int|null
     */
    public function getEntityId(): ?int;

    /**
     * @return \SwiftOtter\Utils\Api\Data\UnifiedSaleItemInterface[]
     */
    public function getAllItems(): ?array;

    /**
     * @return string|null
     */
    public function getIncrementId(): ?string;

    /**
     * @return \Magento\Sales\Api\Data\OrderInterface|\Magento\Quote\Api\Data\CartInterface
     */
    public function get();

    /**
     * @return string
     */
    public function getType(): string;
}
