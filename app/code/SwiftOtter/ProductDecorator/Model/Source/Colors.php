<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/14/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Colors implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => '#ffffff', 'label' => __('White')],
            ['value' => '#000000', 'label' => __('Black')],
            ['value' => '#ff0000', 'label' => __('Red')],
            ['value' => '#fcba03', 'label' => __('Bright Orange')],
            ['value' => '#00f7ff', 'label' => __('Aqua')],
            ['value' => '#0fd620', 'label' => __('Green')],
            ['value' => '#a70bbf', 'label' => __('Fuchsia')]
        ];
    }
}
