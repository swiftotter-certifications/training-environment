<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/13/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Palletizing\Controller\Adminhtml\View;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'SwiftOtter_Palletizing::Configuration';

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
        $resultPage->addBreadcrumb(__('Pallets'), __('Pallets'));
        $resultPage->addBreadcrumb(__('Manage Pallets'), __('Manage Pallets'));
        $resultPage->getConfig()->getTitle()->prepend(__('Pallets'));

        $this->dataPersistor->clear('cms_page');

        return $resultPage;
    }
}