<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data;

interface TierInterface
{
    /**
     * @return int
     */
    public function getId(): ?int;

    /**
     * @return int
     */
    public function getMinTier(): int;

    /**
     * @return int
     */
    public function getMaxTier(): int;

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id): void;

    /**
     * @param int $minTier
     * @return void
     */
    public function setMinTier(int $minTier);

    /**
     * @param int $maxTier
     * @return void
     */
    public function setMaxTier(int $maxTier): void;
}
