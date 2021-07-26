<?php

namespace SwiftOtter\GiftCard\ViewModel\Giftcard\Details;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use SwiftOtter\GiftCard\Api\GiftCardRepositoryInterface;

class View implements ArgumentInterface
{
    /** @var GiftCardRepositoryInterface */
    private $giftCardRepository;

    /** @var RequestInterface */
    private $request;

    public function __construct(
        GiftCardRepositoryInterface $giftCardRepository,
        RequestInterface $request
    ) {
        $this->giftCardRepository = $giftCardRepository;
        $this->request = $request;
    }

    public function getGiftCard()
    {
        $cardId = $this->request->getParam('id');

        return $this->giftCardRepository->getById($cardId);
    }
}
