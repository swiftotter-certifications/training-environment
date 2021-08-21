<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/9/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Test\Integration\Action;

use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\ProductDecorator\Action\FindItemsChildItems as TestSubject;

class FindQuoteItemsChildItemsTest extends TestCase
{
    private $testSubject;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->testSubject = ObjectManager::getInstance()->get(TestSubject::class);

        parent::__construct($name, $data, $dataName);
    }

    public function testSuccess()
    {

    }
}
