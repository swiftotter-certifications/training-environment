<?php
namespace SwiftOtter\Teaching\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\OrderInterface;
use SwiftOtter\Teaching\Api\InvoiceStageInterface;
use SwiftOtter\Teaching\Api\OrderStageInterface;
use SwiftOtter\Teaching\Api\QuoteStageInterface;
use SwiftOtter\Teaching\Api\StageProcessorInterface;

class StageFactory
{
    const MAP = [
        OrderInterface::class => OrderStageInterface::class,
        CartInterface::class => QuoteStageInterface::class,
        InvoiceInterface::class => InvoiceStageInterface::class
    ];

    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Instance name to create
     *
     * @var string
     */
    protected $_instanceName = null;
    private array $map;

    /**
     * Factory constructor
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager, array $map)
    {
        $this->_objectManager = $objectManager;
        $this->map = $map;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param OrderInterface|InvoiceInterface|CartInterface $object
     * @param array $data
     * @return StageProcessorInterface
     */
    public function create($object, array $data = []): StageProcessorInterface
    {
        $type = $this->mapType($object);
        if (!$type) {
            throw new NoSuchEntityException(__("Can't find a map for type %1.", gettype($object)));
        }

        return $this->_objectManager->create($type, $data);
    }

    private function mapType($object): ?string
    {
        foreach (self::MAP as $match => $response) {
            if ($object instanceof $match) {
                return $response;
            }
        }

        return null;
    }
}
