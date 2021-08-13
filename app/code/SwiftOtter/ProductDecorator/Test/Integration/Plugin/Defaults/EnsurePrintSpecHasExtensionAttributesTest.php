<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/12/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Test\Integration\Plugin\Defaults;

use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpecExtensionInterface as ExtensionAttributes;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterfaceFactory as TestSubject;

class EnsurePrintSpecHasExtensionAttributesTest extends TestCase
{
    /** @var TestSubject */
    private $testSubject;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->testSubject = ObjectManager::getInstance()->get(TestSubject::class);
        parent::__construct($name, $data, $dataName);
    }

    public function testAfterCreate()
    {
        $test = $this->testSubject->create();
        $this->assertTrue($test->getExtensionAttributes() instanceof ExtensionAttributes);
    }
}
