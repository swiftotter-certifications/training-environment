<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/2/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class GiftCardActions extends Column
{
    const URL_PATH_EDIT = 'giftcards/view/edit';

    const URL_PATH_DELETE = 'giftcards/view/delete';

    /** @var UrlInterface */
    private $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as & $item) {
            if (!isset($item['id'])) {
                continue;
            }

            $title = $item['code'];

            $item[$this->getData('name')] = [
                'edit' => [
                    'href' => $this->urlBuilder->getUrl(
                        static::URL_PATH_EDIT,
                        [
                            'id' => $item['id'],
                        ]
                    ),
                    'label' => __('Edit'),
                    '__disableTmpl' => true,
                ],
                'delete' => [
                    'href' => $this->urlBuilder->getUrl(
                        static::URL_PATH_DELETE,
                        [
                            'id' => $item['id'],
                        ]
                    ),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete %1', $title),
                        'message' => __('Are you sure you want to delete a %1 record?', $title),
                    ],
                    'post' => true,
                    '__disableTmpl' => true,
                ],
            ];
        }

        return $dataSource;
    }
}