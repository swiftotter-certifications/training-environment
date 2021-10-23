<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 10/23/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Plugin;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Sales\Model\Order;

class UnifiedOrderAlwaysExistsAfterMerge
{
    private UnifiedOrderAlwaysExists $unifiedOrderAlwaysExists;

    public function __construct(UnifiedOrderAlwaysExists $unifiedOrderAlwaysExists)
    {
        $this->unifiedOrderAlwaysExists = $unifiedOrderAlwaysExists;
    }

    public function afterMergeDataObjects(DataObjectHelper $subject, $result, $interfaceName, $firstDataObject, $secondDataObject)
    {
        if (!($firstDataObject instanceof Order)) {
            return $result;
        }

        $this->unifiedOrderAlwaysExists->afterCreate(null, $firstDataObject);
        return $result;
    }
}
