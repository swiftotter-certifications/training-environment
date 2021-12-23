<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/3/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Test\Integration\Model;

use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use SwiftOtter\Catalog\Api\Data\IncomingOrderDetailsInterface;
use SwiftOtter\DownloadProduct\Model\Purchase as TargetClass;
use SwiftOtter\Catalog\Api\Data\IncomingAddressInterface;
use SwiftOtter\Catalog\Model\IncomingPaymentPayload;
use SwiftOtter\Catalog\Model\IncomingShareRequest;
use Magento\OfflinePayments\Model\Checkmo;

class PurchaseTest extends TestCase
{
    /** @var TargetClass */
    private $target;

    protected function setUp(): void
    {
        $this->target = Bootstrap::getObjectManager()->create(
            TargetClass::class,
            [
                'order' => $this->buildOrder()
            ]
        );
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_virtual.php
     */
    public function testPlaceOrder()
    {
        $result = $this->target->placeOrder();
        $this->assertTrue($result->getSuccess());

    }

    private function buildOrder(): IncomingOrderDetailsInterface
    {
        $payload = $this->createConfiguredMock(
            IncomingPaymentPayload::class,
            [
                'getDescription' => '',
                'getNonce' => 'nonce',
                'getType' => Checkmo::PAYMENT_METHOD_CHECKMO_CODE,
                'getCardType' => 'VI',
                'getLastFour' => '1234',
                'getDeviceData' => [],
                'getHash' => '',
                'isLiabilityShifted' => true,
                'isLiabilityShiftPossible' => true,
                'getExpirationMonth' => 01,
                'getExpirationYear' => 2025,
                'getBin' => '1234',
                'getLastTwo' => '12',
                'getEmail' => 'test@test.com',
                'getFirstName' => 'Joseph',
                'getLastName' => 'Maxwell',
                'getCountryCode' => 'US'
            ]
        );

        $share = $this->createConfiguredMock(
            IncomingShareRequest::class,
            [
                'getEnabled' => false
            ]
        );

        $address = $this->createConfiguredMock(
            IncomingAddressInterface::class,
            [
                'getCompany' => 'SwiftOtter',
                'getTelephone' => '123-123-1234',
                'getStreet' => '123 Main St',
                'getCity' => 'Some City',
                'getRegion' => 'KS',
                'getPostalCode' => '12345',
                'getCountry' => 'US'
            ]
        );

        return $this->createConfiguredMock(
            \SwiftOtter\Catalog\Model\IncomingOrder::class,
            [
                'getPayload' => $payload,
                'getShare' => $share,
                'getEmail' => 'test@test.com',
                'getName' => 'Test Name',
                'getPostalCode' => '12345',
                'getAddress' => $address,
                'isSignup' => false,
                'getProductIdentifier' => 'virtual-product',
                'getCurrency' => 'USD',
                'getBodyClasses' => '',
                'getDiscount' => '',
                'getQuantity' => 1,
                'getRecaptchaKey' => '',
                'getChoice' => 'none',
                'getUserToken' => ''
            ]
        );
    }
}
