<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data;

interface PrintMethodInterface
{
    /**
     * @return int
     */
    public function getId(): ?int;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getPriceType(): string;

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id): void;

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void;

    /**
     * @param string $priceType
     * @return void
     */
    public function setPriceType(string $priceType): void;
}
