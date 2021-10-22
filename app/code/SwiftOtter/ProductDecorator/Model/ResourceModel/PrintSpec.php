<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Math\Random;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context as DbContext;

class PrintSpec extends AbstractDb
{
    /** @var Random */
    private $random;

    public function __construct(DbContext $context, Random $random, $connectionName = null)
    {
        $this->random = $random;
        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init('swiftotter_productdecorator_print_spec', 'id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getData('client_id')) {
            $object->setData('client_id', $this->random->getRandomString(15));
        }

        return parent::_beforeSave($object);
    }

    public function updateCartId(int $printSpecId, int $cartId): void
    {
        $this->getConnection()->update(
            $this->getMainTable(),
            [
                'cart_id' => $cartId
            ],
            $this->getConnection()->quoteInto('id = ?', $printSpecId)
        );
    }

    public function getById(int $id)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), ['id'])
            ->where('id = ?', $id);

        return $connection->fetchOne($select);
    }

    public function getIdByClientId(string $clientId): ?int
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), ['id'])
            ->where('client_id = ?', $clientId);

        $output = $connection->fetchOne($select);
        return $output ? (int)$output : null;
    }

    public function delete(\Magento\Framework\Model\AbstractModel $object)
    {
        $object->setData('is_deleted', 1);
        return parent::save($object);
    }

    public function hardDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        return parent::delete($object);
    }
}
