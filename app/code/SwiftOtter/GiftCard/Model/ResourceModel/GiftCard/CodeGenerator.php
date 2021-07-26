<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/30/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Model\ResourceModel\GiftCard;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CodeGenerator extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('gift_card', 'id');
    }

    public function getNewCode(): string
    {
        do {
            $code = $this->generate();

            $select = $this->getConnection()->select();
            $select->from($this->getMainTable(), 'id')
                ->where('code = ?', $code);

            $isset = (bool)$this->getConnection()->fetchOne($select);
        } while ($isset);

        return $code;
    }

    private function generate(): string
    {
        return bin2hex(random_bytes(10));
    }
}