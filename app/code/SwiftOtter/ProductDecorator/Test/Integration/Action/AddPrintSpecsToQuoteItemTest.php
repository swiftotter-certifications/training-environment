<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/13/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Test\Integration\Action;

use Magento\Quote\Api\CartRepositoryInterface as TestSubject;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\ResourceModel\Quote\Collection as QuoteCollection;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

class AddPrintSpecsToQuoteItemTest extends TestCase
{
    /** @var QuoteCollection */
    private $testSubject;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->testSubject = ObjectManager::getInstance()->get(TestSubject::class);
        parent::__construct($name, $data, $dataName);
    }

    protected function setUp(): void
    {
        include __DIR__ . '/../_files/everything_quote.php';
    }

    public function testExecuteHydratesItems()
    {
        $quoteId = ObjectManager::getInstance()->get(QuoteCollection::class)
            ->getFirstItem()
            ->getId();

        /** @var Quote $quote */
        $quote = $this->testSubject->get($quoteId);

        /** @var Quote\Item $quoteItem */
        foreach ($quote->getAllItems() as $quoteItem) {
            $this->assertTrue($quoteItem->getExtensionAttributes()->getPrintSpecItem()->getPrintSpecId() > 0);
        }
    }
}
