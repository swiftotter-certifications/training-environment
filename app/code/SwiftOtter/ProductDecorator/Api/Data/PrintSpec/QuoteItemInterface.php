<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/7/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data\PrintSpec;

interface QuoteItemInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return int|null
     */
    public function getQuoteItemId(): ?int;

    /**
     * @return int|null
     */
    public function getPrintSpecId(): ?int;

    /**
     * @param $id
     * @return void
     */
    public function setId($id): void;

    /**
     * @param int|null $value
     * @return void
     */
    public function setQuoteItemId(?int $value): void;

    /**
     * @param int|null $value
     * @return void
     */
    public function setPrintSpecId(?int $value): void;
}
