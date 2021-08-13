<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/13/21
 * @website https://swiftotter.com
 **/


use SwiftOtter\ProductDecorator\Model\PrintSpec\QuoteItem as PrintSpecQuoteItem;

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$product = include __DIR__ . '/simple_product.php';

/** @var \Magento\Quote\Model\Quote\Address $quoteShippingAddress */
$quoteShippingAddress = $objectManager->create(\Magento\Quote\Model\Quote\Address::class);

/** @var \Magento\Customer\Api\AccountManagementInterface $accountManagement */
$accountManagement = $objectManager->create(\Magento\Customer\Api\AccountManagementInterface::class);

/** @var \Magento\Quote\Model\Quote $quote */
$quote = $objectManager->create(\Magento\Quote\Model\Quote::class);
$quote->setStoreId(
    1
)->setIsActive(
    true
)->setIsMultiShipping(
    false
)->setReservedOrderId(
    'test_order_1'
)->setCustomerEmail(
    'aaa@aaa.com'
);

$quoteItem = $quote->addProduct(
    $product->setData('salable', true),
    2
);

$printSpecQuoteItem = \Magento\TestFramework\ObjectManager::getInstance()->create(PrintSpecQuoteItem::class);
$printSpecQuoteItem->setPrintSpecId(101);
$quoteItem->getExtensionAttributes()->setPrintSpecQuoteItem($printSpecQuoteItem);

$quote->save();
