<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data;

interface LocationInterface
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
    public function getCode(): string;

    /**
     * @return int
     */
    public function getSortOrder(): ?int;

    /**
     * @param int $id
     * @return mixed
     */
    public function setId(int $id): void;

    /**
     * @param string $name
     * @return mixed
     */
    public function setName(string $name): void;

    /**
     * @param string $code
     * @return mixed
     */
    public function setCode(string $code): void;

    /**
     * @param int $sortOrder
     * @return void
     */
    public function setSortOrder(int $sortOrder): void;
}
