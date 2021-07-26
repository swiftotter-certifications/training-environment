<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/5/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Block\Adminhtml\Block\Edit;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton implements ButtonProviderInterface
{
    /** @var UrlInterface */
    private $url;

    public function __construct(UrlInterface $url)
    {
        $this->url = $url;
    }

    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    public function getBackUrl()
    {
        return $this->url->getUrl('*/*/');
    }
}