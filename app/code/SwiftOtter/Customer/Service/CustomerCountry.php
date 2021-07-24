<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/25/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Customer\Service;

use Magento\Checkout\Model\Session;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session\Proxy as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Quote\Model\QuoteRepository;
use SwiftOtter\Customer\Action\CountryIsValid;
use SwiftOtter\Customer\Model\CountrySession;
use SwiftOtter\Customer\Model\ResourceModel\CustomerLookup;
use SwiftOtter\EventQueue\QueueEventManagerInterface;

class CustomerCountry
{
    /** @var CustomerSession */
    private $customerSession;

    /** @var CountrySession */
    private $countrySession;

    /** @var CustomerLookup */
    private $customerLookup;

    /** @var CountryIsValid */
    private $countryIsValid;

    /** @var RequestInterface */
    private $request;

    /** @var QueueEventManagerInterface */
    private $eventManager;

    /** @var CheckoutSession */
    private $checkoutSession;

    /** @var QuoteRepository */
    private $quoteRepository;

    public function __construct(
        CustomerSession $customerSession,
        CountrySession $countrySession,
        CustomerLookup $customerLookup,
        CountryIsValid $countryIsValid,
        RequestInterface $request,
        QueueEventManagerInterface $eventManager,
        CheckoutSession $checkoutSession,
        QuoteRepository $quoteRepository
    ){
        $this->customerSession = $customerSession;
        $this->countrySession = $countrySession;
        $this->customerLookup = $customerLookup;
        $this->countryIsValid = $countryIsValid;
        $this->request = $request;
        $this->eventManager = $eventManager;
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
    }

    public function get(): ?string
    {
        $considerations = [];
        if ($this->customerSession->getCustomerId()) {
            $considerations[] = $this->customerLookup->getCountryFor((int)$this->customerSession->getCustomerId());
        }

        if ($this->checkoutSession->getQuoteId()) {
            $considerations[] = $this->customerLookup->getCountryIdForAddress((int)$this->checkoutSession->getQuoteId());
        }

        $considerations[] = $this->countrySession->getCountry();
        $considerations[] = $this->request->getServer('CloudFront-Viewer-Country');

        $output = array_filter($considerations);
        foreach ($output as $country) {
            if (!$this->countryIsValid->execute($country)) {
                continue;
            }

            return $country;
        }

        return null;
    }

    public function set(?string $country)
    {
        if ($this->customerSession->getCustomerId()) {
            $this->customerLookup->saveCountryCodeFor((int)$this->customerSession->getCustomerId(), $country);
        }

        $this->countrySession->setCountry($country);
    }
}
