<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 9/17/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Model;

use Magento\Framework\Model\AbstractModel;
use SwiftOtter\DownloadProduct\Api\Data\OrderUserInformationInterface;
use SwiftOtter\DownloadProduct\Model\ResourceModel\OrderUserInformation as OrderUserInformationResourceModel;

class OrderUserInformation extends AbstractModel implements OrderUserInformationInterface
{
    protected function _construct()
    {
        $this->_init(OrderUserInformationResourceModel::class);
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return (int)$this->getData('order_id');
    }

    /**
     * @param int $orderId
     */
    public function setOrderId(int $orderId): void
    {
        $this->setData('order_id', $orderId);
    }

    /**
     * @return int|null
     */
    public function getOrderItemId(): ?int
    {
        return $this->getData('order_item_id')
            ? (int)$this->getData('order_item_id')
            : null;
    }

    /**
     * @param int $orderItemId
     */
    public function setOrderItemId(int $orderItemId): void
    {
        $this->setData('order_item_id', $orderItemId);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->getData('name');
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->setData('name', $name);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string)$this->getData('email');
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->setData('email', $email);
    }

    public function getIsShared(): bool
    {
        return (bool)$this->getData('is_shared');
    }

    public function setIsShared(bool $value): void
    {
        $this->setData('is_shared', $value);
    }
}
