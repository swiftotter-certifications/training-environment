<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/20/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Controller\Frontend;

use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use SwiftOtter\GiftCard\Model\Repository\GiftCardRepository;

class Router implements RouterInterface
{
    /** @var GiftCardRepository */
    private $giftCardRepository;

    /** @var ActionFactory */
    private $actionFactory;

    public function __construct(GiftCardRepository $giftCardRepository, ActionFactory $actionFactory)
    {
        $this->giftCardRepository = $giftCardRepository;
        $this->actionFactory = $actionFactory;
    }

    public function match(RequestInterface $request)
    {
        //mywebsite.com/giftcard/CODE12345
        preg_match('/\/giftcard\/([A-Z0-9]+)\//i', $request->getPathInfo(), $matches);
        if (!count($matches)) {
            return null;
        }

        $giftCardCode = array_pop($matches);
        if (!$giftCardCode) {
            return null;
        }

        try {
            $giftCard = $this->giftCardRepository->getByCode($giftCardCode);
        } catch (NoSuchEntityException $ex) {
            return null;
        }

        // mywebsite.com/giftcards/apply/index/code/CODE12345
        $request->setPathInfo('giftcards/apply/index');
        $params = $request->getParams();
        $params['code'] = $giftCard->getCode();
        $request->setParams($params);

        return $this->actionFactory->create(
            Forward::class
        );
    }
}
