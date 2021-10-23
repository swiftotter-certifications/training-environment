<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 10/23/21
 * @website https://swiftotter.com
 **/

namespace Project\Bug6CheckoutDoesntRespond\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Reflection\MethodsMap;
use SwiftOtter\Utils\Api\Data\UnifiedSaleInterface;

class PreventMethodOnUnified
{
    const ENABLED = 'general/bug6/enabled';
    private ScopeConfigInterface $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function afterGetMethodsMap(MethodsMap $subject, ?array $response, ?string $interfaceName): ?array
    {
        if (strpos($interfaceName, UnifiedSaleInterface::class) !== false
            && !$this->scopeConfig->isSetFlag(static::ENABLED)
            && isset($response['get'])
        ) {
            $response = array_diff_key($response, ['get' => null]);
        }

        return $response;
    }
}
