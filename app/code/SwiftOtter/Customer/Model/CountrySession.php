<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/25/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Customer\Model;

use Magento\Customer\Model\Session\Proxy as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Session\Config\ConfigInterface;
use Magento\Framework\Session\SaveHandlerInterface;
use Magento\Framework\Session\SessionManager;
use Magento\Framework\Session\SessionStartChecker;
use Magento\Framework\Session\SidResolverInterface;
use Magento\Framework\Session\StorageInterface;
use Magento\Framework\Session\ValidatorInterface;
use SwiftOtter\Customer\Action\CountryIsValid;
use SwiftOtter\Customer\Model\ResourceModel\CustomerLookup;

class CountrySession extends SessionManager
{
    public function getCountry(): ?string
    {
        return $this->getData('country');
    }

    public function setCountry(?string $country): void
    {
        $this->setData('country', $country);
    }
}
