<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Plugin;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;
use Psr\Log\LoggerInterface;
use SwiftOtter\Customer\Action\SetCurrencyCodeOnQuote;
use SwiftOtter\Customer\Service\CustomerCountry\Proxy as CustomerCountry;

class ForceCurrencyForQuote
{
    /** @var CustomerCountry */
    private $customerCountry;

    /** @var Area */
    private $state;

    /** @var SetCurrencyCodeOnQuote */
    private $setCurrencyCodeOnQuote;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        CustomerCountry $customerCountry,
        State $state,
        SetCurrencyCodeOnQuote $setCurrencyCodeOnQuote,
        LoggerInterface $logger
    ) {
        $this->customerCountry = $customerCountry;
        $this->state = $state;
        $this->setCurrencyCodeOnQuote = $setCurrencyCodeOnQuote;
        $this->logger = $logger;
    }

    public function beforeBeforeSave(Quote $quote)
    {
        try {
            $area = $this->state->getAreaCode();
        } catch(LocalizedException $exception) {
            $area = Area::AREA_ADMINHTML;
        }

        try {
            $country = $quote->getBillingAddress()->getCountryId();
            if ($area === Area::AREA_FRONTEND
                || $area === Area::AREA_WEBAPI_REST) {
                $country = $this->customerCountry->get();
            }

            if (!$country) {
                return null;
            }

            $this->setCurrencyCodeOnQuote->execute(
                $quote,
                $country
            );
        } catch (\Exception $ex) {
            $this->logger->critical('Trying to force currency for quote: ' . $ex->getMessage(), [
                'stack_trace' => $ex->getTraceAsString(),
                'quote_id' => $quote->getId()
            ]);
        }

        return null;
    }
}
