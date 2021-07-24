<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SwiftOtter\DownloadProduct\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use SwiftOtter\DownloadProduct\Attributes;
use SwiftOtter\DownloadProduct\Model\PurchaseType;
use SwiftOtter\EmailList\Model\Source\EmailList;

class CreatePostPurchaseAttributes implements DataPatchInterface
{
    /** @var ModuleDataSetupInterface */
    private $moduleDataSetup;

    /** @var EavSetupFactory */
    private $eavSetupFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            Product::ENTITY,
            Attributes::POST_PURCHASE_MESSAGE,
            [
                'type' => 'text',
                'label' => 'Post-purchase Message',
                'input' => 'textarea',
                'visible' => true,
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'searchable' => true,
                'is_html_allowed_on_front' => false,
                'used_in_product_listing' => true,
                'visible_in_advanced_search' => false,
                'is_used_in_grid' => true
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            Attributes::PURCHASE_TYPE,
            [
                'type' => 'varchar',
                'label' => 'Purchase Type',
                'input' => 'select',
                'source' => PurchaseType::class,
                'visible' => true,
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'searchable' => true,
                'is_html_allowed_on_front' => false,
                'used_in_product_listing' => true,
                'visible_in_advanced_search' => false,
                'is_used_in_grid' => true
            ]
        );
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
