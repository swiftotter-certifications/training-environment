<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Controller\View;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Starting implements HttpGetActionInterface
{
    /** @var PageFactory */
    private $pageFactory;

    public function __construct(PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
    }

    public function execute()
    {
        return $this->pageFactory->create();
    }
}
