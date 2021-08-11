<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/9/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Test\Integration\Plugin;

use Magento\Quote\Model\Cart\Data\CartItemFactory;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

class EnsurePrintSpecQuoteItemAlwaysExistsTest extends TestCase
{
    public function testExtensionAttributesAreSet()
    {
        $cartItem = ObjectManager::getInstance()->get(\Magento\Quote\Api\Data\CartItemInterfaceFactory::class)->create();
        $this->assertTrue($cartItem->getExtensionAttributes()->getPrintSpecQuoteItem() === null);
    }
}
