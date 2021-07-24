<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/15/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Model;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class PurchaseType extends AbstractSource
{
    public function getAllOptions()
    {
        return [
            [
                'value' => 'practice-test',
                'label' => 'Practice Test'
            ],
            [
                'value' => 'study-guide',
                'label' => 'Study Guide'
            ],
            [
                'value' => 'course',
                'label' => 'Course'
            ]
        ];
    }
}