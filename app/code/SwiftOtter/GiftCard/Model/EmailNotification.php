<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/6/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Model;

use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;

class EmailNotification
{
    /** @var StateInterface */
    private $translation;

    /** @var TransportBuilder */
    private $transportBuilder;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var LoggerInterface */
    private $logger;

    /** @var Emulation */
    private $emulation;

    /** @var UrlInterface */
    private $urlBuilder;

    public function __construct(
        Emulation $emulation,
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger,
        UrlInterface $urlBuilder
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->emulation = $emulation;
        $this->urlBuilder = $urlBuilder;
    }

    public function send(int $storeId, GiftCardInterface $giftCard)
    {
        try {
            $this->emulation->startEnvironmentEmulation($storeId, Area::AREA_FRONTEND);

            $transport = $this->transportBuilder->setTemplateIdentifier(
                $this->scopeConfig->getValue('catalog/giftcard/email_template', ScopeInterface::SCOPE_STORE, $storeId)
            )->setTemplateOptions([
                'area' => Area::AREA_FRONTEND,
                'store' => $storeId
            ])->setTemplateVars([
                'url' => $this->urlBuilder->getUrl('giftcard/' . $giftCard->getCode()),
                'giftcard' => $giftCard,
                'recipient_name' => $giftCard->getRecipientName()
            ])->setFromByScope(
                $this->scopeConfig->getValue('catalog/giftcard/email_identity', ScopeInterface::SCOPE_STORE, $storeId),
                $storeId
            )->addTo($giftCard->getRecipientEmail(), $giftCard->getRecipientName())
            ->getTransport();

            $transport->sendMessage();
        } finally {
            $this->emulation->stopEnvironmentEmulation();
        }
    }
}