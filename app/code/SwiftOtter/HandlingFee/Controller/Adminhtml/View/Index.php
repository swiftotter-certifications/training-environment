<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/13/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\Controller\Adminhtml\View;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'SwiftOtter_HandlingFee::Configuration';

    /** @var PageFactory */
    protected $resultPageFactory;

    /** @var DataPersistorInterface */
    private $dataPersistor;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->dataPersistor = $dataPersistor;
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magento_Cms::cms_page');
        $resultPage->addBreadcrumb(__('Handling Fees'), __('Handling Fees'));
        $resultPage->addBreadcrumb(__('Manage Fees'), __('Manage Fees'));
        $resultPage->getConfig()->getTitle()->prepend(__('Fees'));

        $this->dataPersistor->clear('cms_page');

        return $resultPage;
    }
}
