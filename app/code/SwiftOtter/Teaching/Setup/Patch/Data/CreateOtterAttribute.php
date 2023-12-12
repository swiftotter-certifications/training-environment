<?php
declare(strict_types=1);

namespace SwiftOtter\Teaching\Setup\Patch\Data;

use Chapter3\SetupScripts\Attributes;
use Chapter3\SetupScripts\Model\Source\MetalTypes as MetalTypesSource;
use Magento\Catalog\Model\Product as ProductModel;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CreateOtterAttribute implements DataPatchInterface
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
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->addAttribute(
            ProductModel::ENTITY,
            \SwiftOtter\Teaching\Attributes::OTTER_SKU,
            [
                'group' => 'Content',
                'type' => 'static',
                'label' => 'Otter SKU',
                'input' => 'text',
                'required' => false,
                'sort_order' => 50,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
                'visible' => true,
                'is_html_allowed_on_front' => false,
                'visible_on_front' => false
            ]
        );

//        $eavSetup->addAttributeToGroup(
//            ProductModel::ENTITY,
//            'Default',
//            'Content',
//            \SwiftOtter\Teaching\Attributes::OTTER_SKU
//        );
    }

    public function getAliases()
    {
        return [];
    }

    public static function getDependencies()
    {
        return [];
    }
}
