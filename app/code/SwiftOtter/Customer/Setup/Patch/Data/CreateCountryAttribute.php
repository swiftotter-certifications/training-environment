<?php
namespace SwiftOtter\Customer\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Address\Attribute\Source\Country as CountrySource;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use SwiftOtter\DownloadProduct\Attributes;
use SwiftOtter\DownloadProduct\Model\PurchaseType;

class CreateCountryAttribute implements DataPatchInterface
{
    /** @var ModuleDataSetupInterface */
    private $moduleDataSetup;

    /** @var EavSetupFactory */
    private $eavSetupFactory;

    /** @var EavConfig */
    private $eavConfig;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        EavConfig $eavConfig
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
    }

    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            Customer::ENTITY,
            \SwiftOtter\Customer\Attributes::COUNTRY,
            [
                'type' => 'varchar',
                'label' => 'Selected Country',
                'source' => CountrySource::class,
                'input' => 'select',
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

        $sampleAttribute = $this->eavConfig->getAttribute(Customer::ENTITY, \SwiftOtter\Customer\Attributes::COUNTRY);

        $sampleAttribute->setData(
            'used_in_forms',
            ['adminhtml_customer']

        );
        $sampleAttribute->save();
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
