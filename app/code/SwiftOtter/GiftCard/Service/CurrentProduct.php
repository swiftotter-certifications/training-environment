<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Service;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;

class CurrentProduct
{
    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var RequestInterface */
    private $request;

    public function __construct(ProductRepositoryInterface $productRepository, RequestInterface $request)
    {
        $this->productRepository = $productRepository;
        $this->request = $request;
    }

    public function get(): ?ProductInterface
    {
        if (!$this->request->getParam('id')) {
            return null;
        }

        return $this->productRepository->getById((int)$this->request->getParam('id'));
    }
}