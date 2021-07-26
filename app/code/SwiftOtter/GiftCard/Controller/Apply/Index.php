<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/20/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Controller\Apply;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use SwiftOtter\GiftCard\Model\Endpoint\GiftCardRetriever;

class Index extends Action implements HttpGetActionInterface
{
    /** @var GiftCardRetriever */
    private $giftCardRetriever;

    /** @var CheckoutSession */
    private $checkoutSession;

    /** @var CartRepositoryInterface */
    private $cartRepository;

    public function __construct(
        Context $context,
        GiftCardRetriever $giftCardRetriever,
        CheckoutSession $checkoutSession,
        CartRepositoryInterface $cartRepository
    ) {
        $this->giftCardRetriever = $giftCardRetriever;
        $this->checkoutSession = $checkoutSession;

        parent::__construct($context);
        $this->cartRepository = $cartRepository;
    }

    public function execute()
    {
        $code = $this->getRequest()->getParam('code');
        $quote = $this->checkoutSession->getQuote();

        if (!$quote->getId()) {
            $this->cartRepository->save($quote);
            $this->checkoutSession->setQuoteId($quote->getId());
        }

        $this->giftCardRetriever->applyCustomer((int)$quote->getId(), $code);
    }
}