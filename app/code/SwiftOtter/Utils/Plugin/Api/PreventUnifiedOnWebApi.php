<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/25/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Plugin\Api;

use Magento\Framework\Reflection\MethodsMap;
use SwiftOtter\Utils\Api\Data\UnifiedSaleInterface;
use SwiftOtter\Utils\Api\Data\UnifiedSaleItemInterface;

class PreventUnifiedOnWebApi
{
    public function afterIsMethodValidForDataField(MethodsMap $target, $response, $type, $methodName): bool
    {
        $type = ltrim($type, '\\');
        if ($type === UnifiedSaleInterface::class
            || $type === UnifiedSaleItemInterface::class
            || (strpos($type, 'ExtensionInterface') !== false && strpos($methodName, "Unified") !== false)) {
            return false;
        }

        return $response;
    }
}
