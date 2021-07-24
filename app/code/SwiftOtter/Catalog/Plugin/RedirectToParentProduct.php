<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/3/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Plugin;

use Magento\Catalog\Controller\Product\View as ProductViewController;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\UrlInterface;
use SwiftOtter\Catalog\Attributes;

class RedirectToParentProduct
{
    /** @var RequestInterface */
    private $request;

    /** @var ProductCollectionFactory */
    private $productCollectionFactory;

    /** @var ProductResource */
    private $productResource;

    /** @var RedirectFactory */
    private $redirectFactory;

    /** @var UrlInterface */
    private $url;

    public function __construct(
        RequestInterface $request,
        ProductResource $productResource,
        ProductCollectionFactory $productCollectionFactory,
        RedirectFactory $redirectFactory,
        UrlInterface $url
    ) {
        $this->request = $request;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productResource = $productResource;
        $this->redirectFactory = $redirectFactory;
        $this->url = $url;
    }

    public function aroundExecute(ProductViewController $subject, callable $proceed)
    {
        if ($this->request->isPost()) {
            return $proceed();
        }

        $productId = (int)$this->request->getParam('id');
        if (!$productId) {
            return $proceed();
        }

        $redirectSku = $this->productResource->getAttributeRawValue($productId, Attributes::ATTRIBUTE_PARENT_PRODUCT_SKU, 0);
        if (!$redirectSku) {
            return $proceed();
        }

        $redirectProduct = $this->productCollectionFactory->create()
            ->addFieldToFilter('sku', $redirectSku)
            ->addAttributeToSelect(['url_path', 'url_key'])
            ->getFirstItem();

        return $this->redirectFactory->create()
            ->setUrl($redirectProduct->getProductUrl());
    }
}
