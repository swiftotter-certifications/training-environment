<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/3/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Plugin;

use Magento\Checkout\Model\DefaultConfigProvider;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

class AddEmbeddedPathToSuccess
{
    /** @var UrlInterface */
    private $url;

    /** @var RequestInterface */
    private $request;

    public function __construct(UrlInterface $url, RequestInterface $request)
    {
        $this->url = $url;
        $this->request = $request;
    }

    public function afterGetDefaultSuccessPageUrl(DefaultConfigProvider $configProvider, string $result)
    {
        $embedded = (bool)$this->request->getParam('embedded');
        $query = [];
        if ($embedded) {
            $query['_query'] = ['embedded' => 1];
        }

        return $this->url->getUrl('checkout/onepage/success/', $query);
    }
}
