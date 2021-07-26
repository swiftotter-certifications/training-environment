<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/30/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Controller\Adminhtml\View;

use Magento\Backend\App\Action as AppAction;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

class Edit extends AppAction implements HttpGetActionInterface
{
    /** @var PageFactory */
    private $pageFactory;

    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->prepend(__('View Gift Card'));

        return $page;
    }
}