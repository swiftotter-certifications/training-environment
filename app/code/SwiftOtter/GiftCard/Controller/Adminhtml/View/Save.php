<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/5/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Controller\Adminhtml\View;

use Magento\Backend\App\Action as AppAction;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use SwiftOtter\GiftCard\Model\GiftCardFactory;
use SwiftOtter\GiftCard\Model\Repository\GiftCardRepository;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard\CodeGenerator;

class Save extends AppAction implements HttpPostActionInterface
{
    /** @var GiftCardRepository */
    private $giftCardRepository;

    /** @var GiftCardFactory */
    private $giftCardFactory;

    public const ADMIN_RESOURCE = 'SwiftOtter_GiftCard::management';

    /** @var CodeGenerator */
    private $codeGenerator;

    public function __construct(
        Context $context,
        GiftCardRepository $giftCardRepository,
        GiftCardFactory $giftCardFactory,
        CodeGenerator $codeGenerator
    ) {
        AppAction::__construct($context);
        $this->giftCardRepository = $giftCardRepository;
        $this->giftCardFactory = $giftCardFactory;
        $this->codeGenerator = $codeGenerator;
    }

    public function execute()
    {
        $params = $this->getRequest()->getParams();

        if ($this->getRequest()->getParam('id')) {
            $giftCard = $this->giftCardRepository->getById(
                (int)$this->getRequest()->getParam('id')
            );
        } else {
            $giftCard = $this->giftCardFactory->create();
            unset($params['id']);
        }


        if ((!isset($params['initial_value']) || !$params['initial_value'])
            && isset($params['current_value'])
            && $params['current_value']) {
            $params['initial_value'] = $params['current_value'];
        }

        $giftCard->setData($params);

        if (!$giftCard->getCode()) {
            $giftCard->setCode($this->codeGenerator->getNewCode());
        }

        $this->giftCardRepository->save($giftCard);

        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirect->setPath('*/*/index');

        $this->messageManager->addSuccessMessage(__('Giftcard was successfully saved.'));

        return $redirect;
    }
}
