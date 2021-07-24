<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Plugin;

use Magento\Quote\Model\Quote\Payment;
use SwiftOtter\Checkout\Model\RegisterPasswordTransport;

class CopyRegisterPassword
{
    /** @var RegisterPasswordTransport */
    private $registerPasswordTransport;

    public function __construct(RegisterPasswordTransport $registerPasswordTransport)
    {
        $this->registerPasswordTransport = $registerPasswordTransport;
    }

    public function afterImportData(Payment $subject, Payment $response, array $data): Payment
    {
        if (!isset($data['extension_attributes'])
            || !is_object($data['extension_attributes'])) {
            return $response;
        }

        $this->registerPasswordTransport->setPassword($data['extension_attributes']->getRegisterPassword());
        return $response;
    }
}
