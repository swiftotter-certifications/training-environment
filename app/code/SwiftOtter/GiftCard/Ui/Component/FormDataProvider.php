<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/6/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Ui\Component;

use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use SwiftOtter\GiftCard\Model\GiftCard;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard\CollectionFactory as GiftCardCollectionFactory;

class FormDataProvider extends ModifierPoolDataProvider
{
    /** @var array */
    private $formData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        GiftCardCollectionFactory $collectionFactory,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    public function getData()
    {
        if (isset($this->formData)) {
            return $this->formData;
        }

        $items = $this->collection->getItems();
        /** @var GiftCard $giftcard */
        foreach ($items as $giftcard) {
            $this->formData[$giftcard->getId()] = $giftcard->getData();
        }

        return $this->formData;
    }
}