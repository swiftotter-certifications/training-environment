<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 10/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GraphQL\Model\Resolver;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;
use SwiftOtter\GiftCard\Api\GiftCardRepositoryInterface;

class Giftcards implements ResolverInterface
{
    const ALLOWED_USER_TYPES = [UserContextInterface::USER_TYPE_INTEGRATION, UserContextInterface::USER_TYPE_ADMIN];

    private GiftCardRepositoryInterface $giftCardRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        GiftCardRepositoryInterface $giftCardRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->giftCardRepository = $giftCardRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (empty($args['filter']['code'])) {
            throw new GraphQlNoSuchEntityException(__('You must specify a code.'));
        }

        [$giftCards, $totalItems] = $this->locateGiftCards($args ?? [], $context);

        return [
            'items' => array_map([$this, 'convertGiftCardToData'], $giftCards),
            'total_count' => $totalItems,
        ];
    }

    private function locateGiftCards(array $args, $context)
    {
        $this->searchCriteriaBuilder->addFilter('code', $args['filter']['code']);
        if (!empty($args['filter']['status'])) {
            $this->searchCriteriaBuilder->addFilter('status', $args['filter']['status']);
        }

        $items = $this->giftCardRepository->getList($this->searchCriteriaBuilder->create());
        $giftCardResponse = array_filter($items->getItems(), function(GiftCardInterface $giftCard) use ($context) {
            return in_array($context->getUserType(), self::ALLOWED_USER_TYPES)
                || ($context->getUserId() && $context->getUserId() === $giftCard->getCustomerId());
        });

        return [$giftCardResponse, $items->getTotalCount()];
    }

    private function convertGiftCardToData(GiftCardInterface $giftCard)
    {
        return [
            GiftCardInterface::ID => $giftCard->getId(),
            'assigned_customer_id' => $giftCard->getCustomerId(),
            'code' => $giftCard->getCode(),
            'status' => $giftCard->getStatus(),
            'initial_value' => $giftCard->getInitialValue(),
            'current_value' => $giftCard->getCurrentValue(),
            'created_at' => $giftCard->getCreatedAt(),
            'updated_at' => $giftCard->getUpdatedAt(),
            'recipient_email' => $giftCard->getRecipientEmail(),
            'recipient_name' => $giftCard->getRecipientName()
        ];
    }
}
