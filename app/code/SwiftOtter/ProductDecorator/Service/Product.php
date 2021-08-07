<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Service;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\RequestInterface;
use SwiftOtter\ProductDecorator\Attributes;
use SwiftOtter\Repository\Api\FastProductRepositoryInterface;

class Product
{
    /** @var RequestInterface */
    private $request;

    /** @var FastProductRepositoryInterface */
    private $productRepository;

    public function __construct(
        RequestInterface $request,
        FastProductRepositoryInterface $productRepository
    ) {
        $this->request = $request;
        $this->productRepository = $productRepository;
    }

    public function getProduct(): ?ProductInterface
    {
        if (!($this->request instanceof HttpRequest)
            || $this->request->getFullActionName() !== 'catalog_product_view'
            || !$this->request->get('id')) {
            return null;
        }

        return $this->productRepository->getById((int)$this->request->get('id'), null, null, null, [Attributes::ENABLED]);
    }
}
