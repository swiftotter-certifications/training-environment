<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/19/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\CategoryAsProduct\Setup;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use SwiftOtter\CategoryAsProduct\Attributes;
use SwiftOtter\CategoryAsProduct\Model\Product\Type;

class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Upgrades data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(Product::ENTITY,
            Attributes::REDIRECT_TO_CATEGORY,
            [
                'type' => 'int',
                'label' => 'Redirect to Category',
                'input' => 'select',
                'visible' => true,
                'required' => true,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'source' => \SwiftOtter\CategoryAsProduct\Model\Source\Category::class,
                'searchable' => true,
                'is_html_allowed_on_front' => false,
                'used_in_product_listing' => true,
                'visible_in_advanced_search' => false,
                'is_used_in_grid' => true,
                'apply_to' => Type::TYPE_ID
            ]
        );
    }
}