<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/28/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\ViewModel;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Model\OrderRepository;

class OrderDetails implements ArgumentInterface
{
    /** @var AuthorizationInterface */
    private $authorization;

    /** @var UrlInterface */
    private $urlBuilder;

    /** @var FormKey */
    private $formKey;

    /** @var RequestInterface */
    private $request;
    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;
    /** @var OrderRepository */
    private $orderRepository;
    /** @var FilterBuilder */
    private $filterBuilder;
    /** @var FilterGroupBuilder */
    private $filterGroupBuilder;

    public function __construct(
        AuthorizationInterface $authorization,
        UrlInterface $urlBuilder,
        FormKey $formKey,
        RequestInterface $request,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderRepository $orderRepository,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder
    ) {
        $this->authorization = $authorization;
        $this->urlBuilder = $urlBuilder;
        $this->formKey = $formKey;
        $this->request = $request;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderRepository = $orderRepository;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
    }

    public function isAllowed(): bool
    {
        return $this->authorization->isAllowed('SwiftOtter_OrderExport::OrderExport');
    }

    public function getButtonMessage(): string
    {
        return (string)__('Send Order to Fulfillment');
    }

    public function getConfig(): array
    {
        $orders = $this->orderRepository->getList(
            $this->searchCriteriaBuilder
                ->addFilter('entity_id', [/** order ids */], 'in')
                ->create()
        );

        return [
            'sending_message' => __('Sending...'),
            'original_message' => $this->getButtonMessage(),
            'upload_url' => $this->urlBuilder->getUrl(
                'order_export/export/run',
                [
                    'order_id' => (int)$this->request->getParam('order_id')
                ]
            ),
            'form_key' => $this->formKey->getFormKey()
        ];
    }
}