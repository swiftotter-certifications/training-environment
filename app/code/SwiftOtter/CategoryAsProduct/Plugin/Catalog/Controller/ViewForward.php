<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc., 2018/04/18
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\CategoryAsProduct\Plugin\Catalog\Controller;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Controller\ResultInterface;
use SwiftOtter\CategoryAsProduct\Model\Product\Type;

class ViewForward
{
    private $redirectFactory;
    private $registry;

    public function __construct(
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->registry = $registry;
    }

    public function afterExecute(\Magento\Catalog\Controller\Product\View $controller, ResultInterface $result)
    {
        if (!$this->registry->registry('current_product')) {
            return $result;
        }

        /** @var ProductInterface $product */
        $product = $this->registry->registry('current_product');
        if ($product->getTypeId() !== Type::TYPE_ID) {
            return $result;
        }

        $redirect = $this->redirectFactory->create();
        $redirect->setUrl($product->getProductUrl());
        $redirect->setHttpResponseCode(301);

        return $redirect;
    }
}