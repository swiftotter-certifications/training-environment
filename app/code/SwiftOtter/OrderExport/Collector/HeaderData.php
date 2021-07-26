<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/28/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Collector;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderAddressRepositoryInterface;
use Magento\Store\Model\ScopeInterface;
use SwiftOtter\OrderExport\Api\DataCollectorInterface;

class HeaderData implements DataCollectorInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var OrderAddressRepositoryInterface
     */
    private $orderAddressRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        OrderAddressRepositoryInterface $orderAddressRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->orderAddressRepository = $orderAddressRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function collect(OrderInterface $order, \SwiftOtter\OrderExport\Model\HeaderData $headerData): array
    {
        $address = $this->getShippingAddressFor($order);

        $output = [
            'password' => $this->scopeConfig->getValue(
                'sales/order_export/password',
                ScopeInterface::SCOPE_STORES,
                $order->getStoreId()
            ),
            'id' => $order->getIncrementId(),
            'currency' => $order->getBaseCurrencyCode(),
            'customer_notes' => $order->getExtensionAttributes()->getBoldOrderComment(),
            'merchant_notes' => $headerData->getMerchantNotes(),
            'discount' => $order->getBaseDiscountAmount(),
            'total' => $order->getBaseGrandTotal()
        ];

        if ($address) {
            $output['shipping'] = [
                'name' => $address->getFirstname() . ' ' . $address->getLastname(),
                'address' => $address->getStreet() ? implode(', ', $address->getStreet()) : '',
                'city' => $address->getCity(),
                'state' => $address->getRegionCode(),
                'postcode' => $address->getPostcode(),
                'country' => $address->getCountryId(),
                'amount' => $order->getBaseShippingAmount(),
                'method' => $order->getShippingDescription(),
                'ship_on' => $headerData->getShipDate()->format('d/m/Y')
            ];
        }

        return $output;
    }

    private function getShippingAddressFor(OrderInterface $order): ?OrderAddressInterface
    {
        $addresses = $this->orderAddressRepository->getList(
            $this->searchCriteriaBuilder
                ->addFilter('parent_id', $order->getEntityId())
                ->addFilter('address_type', 'shipping')
                ->create()
        );

        if (!count($addresses->getItems())) {
            return null;
        } else {
            $items = $addresses->getItems();
            return reset($items);
        }
    }
}