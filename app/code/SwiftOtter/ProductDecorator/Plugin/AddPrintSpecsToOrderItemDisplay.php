<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin;

use Magento\Sales\Block\Adminhtml\Items\Column\DefaultColumn;
use SwiftOtter\ProductDecorator\Action\PrintSpecToArray;

class AddPrintSpecsToOrderItemDisplay
{
    /** @var PrintSpecToArray */
    private $printSpecToArray;

    public function __construct(PrintSpecToArray $printSpecToArray)
    {
        $this->printSpecToArray = $printSpecToArray;
    }

    public function afterGetOrderOptions(DefaultColumn $subject, array $output): array
    {
        $item = $subject->getItem();

        $details = $this->printSpecToArray->execute($item->getExtensionAttributes()->getUnified());
        $output = [];

        foreach ($details as $detail) {
            $output[] = [
                'label' => $detail['title'],
                'value' => ''
            ];

            foreach ($detail['values'] as $value) {
                foreach ($value as $spec => $config) {
                    $output[] = [
                        'label' => ucfirst($spec),
                        'value' => $config
                    ];
                }
            }
        }

        return array_merge($output, $output);
    }
}
