<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Plugin;

use Magento\Checkout\Model\DefaultConfigProvider;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\Api\CustomAttributesDataInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Ui\Component\Form\Element\Multiline;
use SwiftOtter\Customer\Service\CustomerCountry;

class PushBillingAddressForGuests
{
    /** @var CheckoutSession */
    private $checkoutSession;

    /** @var HttpContext */
    private $httpContext;

    /** @var AddressMetadataInterface */
    private $addressMetadata;

    /** @var CustomerCountry */
    private $customerCountry;

    public function __construct(
        CheckoutSession $checkoutSession,
        HttpContext $httpContext,
        AddressMetadataInterface $addressMetadata,
        CustomerCountry $customerCountry
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->httpContext = $httpContext;
        $this->addressMetadata = $addressMetadata;
        $this->customerCountry = $customerCountry;
    }

    public function afterGetConfig(DefaultConfigProvider $subject, array $response): array
    {
        $quote = $this->checkoutSession->getQuote();

        $shippingAddressFromData = $this->getAddressFromData($quote->getShippingAddress());
        $billingAddressFromData = $this->getAddressFromData($quote->getBillingAddress());
        if (empty($shippingAddressFromData['country_id'])) {
            $shippingAddressFromData['country_id'] = $this->customerCountry->get();
        }

        if (empty($billingAddressFromData['country_id'])) {
            $billingAddressFromData['country_id'] = $this->customerCountry->get();
        }

        $response['shippingAddressFromData'] = $shippingAddressFromData;
        if ($shippingAddressFromData != $billingAddressFromData) {
            $response['billingAddressFromData'] = $billingAddressFromData;
        }

        return $response;
    }

    private function getAddressFromData(AddressInterface $address)
    {
        $addressData = [];
        $attributesMetadata = $this->addressMetadata->getAllAttributesMetadata();
        foreach ($attributesMetadata as $attributeMetadata) {
            if (!$attributeMetadata->isVisible()) {
                continue;
            }
            $attributeCode = $attributeMetadata->getAttributeCode();
            $attributeData = $address->getData($attributeCode);
            if ($attributeData) {
                if ($attributeMetadata->getFrontendInput() === Multiline::NAME) {
                    $attributeData = \is_array($attributeData) ? $attributeData : explode("\n", $attributeData);
                    $attributeData = (object)$attributeData;
                }
                if ($attributeMetadata->isUserDefined()) {
                    $addressData[CustomAttributesDataInterface::CUSTOM_ATTRIBUTES][$attributeCode] = $attributeData;
                    continue;
                }
                $addressData[$attributeCode] = $attributeData;
            }
        }
        return $addressData;
    }

    public function isLoggedIn()
    {
        return (bool)$this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
    }
}
