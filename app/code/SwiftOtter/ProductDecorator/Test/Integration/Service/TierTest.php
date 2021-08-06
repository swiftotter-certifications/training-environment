<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Test\Integration\Service;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\ProductDecorator\Service\Tier as TestSubject;

class TierTest extends TestCase
{
    /** @var TestSubject */
    private $testSubject;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->testSubject = ObjectManager::getInstance()->get(TestSubject::class);

        parent::__construct($name, $data, $dataName);
    }

    protected function setUp(): void
    {
        include __DIR__ . '/../_files/tier_rollback.php';
        include __DIR__ . '/../_files/default_tiers.php';
    }

    /**
     * @dataProvider buildTierList
     */
    public function testReturnsCorrectTier($quantity, $expected)
    {
        $this->assertEquals(
            $expected,
            $this->testSubject->getTier('simple', $quantity)->getMinTier(),
        "Ensures we achieve input ${quantity} and the expected min tier output, ${expected}"
        );
    }

    public function buildTierList()
    {
        return [
            [1, 1],
            [8, 6],
            [50, 41]
        ];
    }

    public function testThrowsExceptionForInvalidTier()
    {
        try {
            $this->testSubject->getTier('simple', 1000);
            $this->assertTrue(false, 'Exception was NOT thrown when the tier was unabled to be found');
        } catch (NoSuchEntityException $ex) {
            $this->assertTrue(true, 'Exception was thrown when no tier was located.');
        }
    }


}
