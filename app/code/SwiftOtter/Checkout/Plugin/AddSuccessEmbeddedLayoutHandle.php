<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/29/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Plugin;

use Magento\Checkout\Controller\Index\Index as Controller;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultInterface as Result;
use Magento\Framework\View\Layout\Builder;
use Magento\Framework\View\LayoutInterface;
use Magento\Framework\View\Result\Page;

class AddSuccessEmbeddedLayoutHandle
{
    /** @var RequestInterface */
    private $request;

    /** @var LayoutInterface */
    private $layout;

    private $doneIt = false;

    public function __construct(RequestInterface $request, LayoutInterface $layout)
    {
        $this->request = $request;
        $this->layout = $layout;
    }

    public function beforeBuild(Builder $subject)
    {
        if (!($this->request instanceof Http)
            || $this->request->getFullActionName() !== 'checkout_onepage_success'
            || !$this->request->getParam('embedded')
            || $this->doneIt) {
            return null;
        }

        $this->doneIt = true;
        $this->layout->getUpdate()->addHandle('checkout_success_embedded');

        return null;
    }
}
