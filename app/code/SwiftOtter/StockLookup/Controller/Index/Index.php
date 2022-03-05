<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 2/9/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\StockLookup\Controller\Index;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;

class Index implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    private RequestInterface $request;
    private PageFactory $pageFactory;

    public function __construct(
        RequestInterface $request,
        PageFactory $pageFactory
    ) {
        $this->request = $request;
        $this->pageFactory = $pageFactory;
    }

    public function execute()
    {
        // TODO: Implement execute() method.
    }
}
