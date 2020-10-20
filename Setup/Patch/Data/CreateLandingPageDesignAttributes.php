<?php

namespace EricMorand\CategoryLandingPage\Setup\Patch\Data;

use Magento\Catalog\Model\Category\Attribute\Source\Layout as LayoutModel;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Theme\Model\Theme\Source\Theme;

class CreateLandingPageDesignAttributes implements DataPatchInterface
{
    /** @var EavSetupFactory */
    protected $_eavSetupFactory;

    /** @var ModuleDataSetupInterface  */
    protected $_setup;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $setup
    ) {
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->_setup = $setup;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $this->_setup]);

        // Custom Design
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'landing_custom_design',
            [
                'type' => 'varchar',
                'label' => 'Category Landing Page Custom Design',
                'input' => 'select',
                'source' => Theme::class,
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Design',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
            ]
        );

        // Page Layout
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'landing_page_layout',
            [
                'type' => 'varchar',
                'label' => 'Category Landing Page Page Layout',
                'input' => 'select',
                'source' => LayoutModel::class,
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Design',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
            ]
        );
    }
}