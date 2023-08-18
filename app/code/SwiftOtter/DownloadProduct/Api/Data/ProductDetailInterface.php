<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/20/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Api\Data;

use SwiftOtter\DownloadProduct\Api\PriceResponseInterface;

interface ProductDetailInterface
{
    const TYPE_STUDY_GUIDE = 'study_guide';
    const TYPE_PREP_COURSE = 'prep_course';
    const TYPE_PRACTICE_TEST = 'practice_test';
    const TYPE_SIMPLE = 'simple';

    const DISPLAY_TYPE_PRODUCT = 'product';
    const DISPLAY_TYPE_ADDON = 'addon';

    /**
     * @return int|null
     */
    public function getQty(): ?int;

    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @return \SwiftOtter\DownloadProduct\Api\PriceResponseInterface
     */
    public function getPrice(): PriceResponseInterface;

    /**
     * @return string
     */
    public function getName(): ?string;

    /**
     * @return string
     */
    public function getShortName(): string;

    /**
     * @return string
     */
    public function getHelpText(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getImage(): string;

    /**
     * @return bool
     */
    public function getIsPreferred(): bool;

    /**
     * @return int
     */
    public function getSortOrder(): int;

    /**
     * @return bool
     */
    public function getIsPurchased(): bool;

    /**
     * @return bool
     */
    public function getIsActive(): bool;

    /**
     * @return int
     */
    public function getTestId(): int;

    /**
     * @return string
     */
    public function getDisplayType(): string;

    /**
     * @return \SwiftOtter\DownloadProduct\Api\Data\ProductDetailInterface[]
     */
    public function getChildren(): array;

    /**
     * @return string
     */
    public function getMessage(): string;
}
