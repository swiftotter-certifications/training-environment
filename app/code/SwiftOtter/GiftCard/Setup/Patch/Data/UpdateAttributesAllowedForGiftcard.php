<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SwiftOtter\GiftCard\Setup\Patch\Data;

use Magento\Catalog\Model\Product as ProductType;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Setup\EavSetupFactory;
use SwiftOtter\GiftCard\Model\Type\GiftCard;

class UpdateAttributesAllowedForGiftcard implements DataPatchInterface
{
    /** @var ModuleDataSetupInterface */
    private $moduleDataSetup;

    /** @var EavSetupFactory */
    private $eavSetupFactory;

    public function __construct(ModuleDataSetupInterface $moduleDataSetup, EavSetupFactory $eavSetupFactory)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $applyTo = explode(
            ',',
            $eavSetup->getAttribute(ProductType::ENTITY, 'price', 'apply_to')
        );

        if (!in_array(GiftCard::TYPE_CODE, $applyTo)) {
            $applyTo[] = GiftCard::TYPE_CODE;
            $eavSetup->updateAttribute(
                ProductType::ENTITY,
                'price',
                'apply_to',
                implode(',', $applyTo)
            );
        }
    }

    public function getAliases()
    {
        return [];
    }

    public static function getDependencies()
    {
        return [

        ];
    }
}
