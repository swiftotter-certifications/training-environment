<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/14/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Controller\Escape;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;

class Index implements HttpGetActionInterface
{
    private PageFactory $pageFactory;

    public function __construct(PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
    }

    public function execute()
    {
        return $this->pageFactory->create();
    }
}
