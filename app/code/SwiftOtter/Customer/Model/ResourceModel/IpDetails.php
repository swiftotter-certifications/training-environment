<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc., 2017/12/18
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Customer\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Context as DbContext;
use SwiftOtter\Customer\Model\IpDetailsFactory;

class IpDetails extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    private $factory;

    public function __construct(
        DbContext $context,
        IpDetailsFactory $factory,
        string $connectionName = null
    ) {
        $this->factory = $factory;
        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init('test_ip_details', 'id');
    }

    protected function isObjectNotNew(\Magento\Framework\Model\AbstractModel $object)
    {
        return $object->getId() > 0;
    }

    public function getIdByIp(string $ip)
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), ['id'])
            ->where('ip = ?', $ip);

        return (int)$this->getConnection()->fetchOne($select);
    }

    public function deleteByIp(string $ip)
    {
        $this->getConnection()->delete(
            $this->getMainTable(),
            $this->getConnection()->quoteInto('ip = ?', $ip)
        );
    }

    public function getCurrencyByIp(string $ip)
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), ['currency'])
            ->where('ip = ?', $ip);

        return (string)$this->getConnection()->fetchOne($select);
    }

    public function getDetailsByIp(string $ip)
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable())
            ->where('ip = ?', $ip);

        $details = $this->getConnection()->fetchRow($select);
        $output = $this->factory->create();

        if (is_array($details) && count($details) > 0) {
            $output->setData($details);
        }

        return $output;
    }

    public function getCurrencyByCountryCode(string $countryCode): ?string
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'currency')
            ->where('country_code = ?', $countryCode);

        $value = $this->getConnection()->fetchOne($select);
        return $value ? (string)$value : null;
    }
}
