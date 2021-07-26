<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Palletizing\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;

class Index implements HttpGetActionInterface
{
    /** @var RequestInterface */
    private $request;

    const ALLOWED_ACTIONS = [
        'catalog_product_view'
    ];

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        if (!($this->request instanceof HttpRequest)) {
            return null;
        }

        if (!in_array($this->request->getFullActionName(), self::ALLOWED_ACTIONS)) {
            return null;
            // do something bad
        }

        // load something from the

        $test = "do something";
    }

}