<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 10/23/21
 * @website https://swiftotter.com
 **/

namespace Project\Bug6CheckoutDoesntRespond\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class EngagedState
{
    const ENABLED = 'general/bug6/enabled';

    private bool $engaged = false;
    private ScopeConfigInterface $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isEngaged(): bool
    {
        return $this->engaged
            && !$this->scopeConfig->isSetFlag(self::ENABLED);
    }

    public function setEngaged(bool $engaged): void
    {
        $this->engaged = $engaged;
    }


}
