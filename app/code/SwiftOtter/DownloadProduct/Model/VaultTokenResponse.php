<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc., 2019/09/28
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Model;

use Magento\Vault\Api\Data\PaymentTokenInterface;
use SwiftOtter\DownloadProduct\Api\Data\ExternalPaymentVaultTokenInterface;

class VaultTokenResponse implements ExternalPaymentVaultTokenInterface
{
    /** @var PaymentTokenInterface */
    private $token;

    public function __construct(PaymentTokenInterface $token)
    {
        $this->token = $token;
    }

    public function getPublicHash(): string
    {
        return $this->token->getPublicHash();
    }

    public function getPaymentMethodCode(): string
    {
        return $this->token->getPaymentMethodCode();
    }

    private function getDetails(): array
    {
        return json_decode($this->token->getTokenDetails() ?: '{}', true);
    }

    public function getType(): string
    {
        return $this->getDetails()['type'] ?? '';
    }

    public function getLast4(): string
    {
        return $this->getDetails()['maskedCC'] ?? '';
    }

    public function getExpirationDate(): string
    {
        return $this->getDetails()['expirationDate'] ?? '';
    }

}