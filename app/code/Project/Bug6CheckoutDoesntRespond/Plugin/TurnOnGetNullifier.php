<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 10/23/21
 * @website https://swiftotter.com
 **/

namespace Project\Bug6CheckoutDoesntRespond\Plugin;

use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Reflection\MethodsMap;
use Magento\Sales\Api\Data\OrderItemInterface;
use Project\Bug6CheckoutDoesntRespond\Model\EngagedState;
use SwiftOtter\Utils\Api\Data\UnifiedSaleInterface;
use SwiftOtter\Utils\Api\Data\UnifiedSaleItemInterface;

class TurnOnGetNullifier
{
    private EngagedState $engagedState;
    private MethodsMap $methodsMap;

    public function __construct(EngagedState $engagedState, MethodsMap $methodsMap)
    {
        $this->engagedState = $engagedState;
        $this->methodsMap = $methodsMap;
    }

    public function aroundBuildOutputDataArray(DataObjectProcessor $dataObjectProcessor, callable $proceed, $dataObject, $dataObjectType)
    {
        if (stripos($dataObjectType,UnifiedSaleInterface::class) !== false) {
            $methods = $this->methodsMap->getMethodsMap($dataObjectType);
            $this->engagedState->setEngaged(
                empty($methods['get']['type'])
                || stripos($methods['get']['type'], OrderItemInterface::class) === false
            );
        }

        $output = $proceed(...array_slice(func_get_args(), 2));

        if (stripos($dataObjectType,UnifiedSaleInterface::class) !== false) {
            $this->engagedState->setEngaged(false);
        }

        return $output;
    }

}
