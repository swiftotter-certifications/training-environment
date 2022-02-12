<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 2/9/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\StockLookup\Controller\Index;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;

class Index implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    private RequestInterface $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        // TODO: Implement execute() method.
    }
}
