<?php

namespace EricMorand\CategoryLandingPage\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Setup\EavSetupFactory;

class CreateLandingPageSEOAttributes implements DataPatchInterface
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

        // URL key
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'landing_url_key',
            [
                'type' => 'varchar',
                'label' => 'Category Landing Page URL Key',
                'input' => 'text',
                'required' => false,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
            ]
        );

        // Meta Title
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'landing_meta_title',
            [
                'type' => 'varchar',
                'label' => 'Category Landing Page Meta Title',
                'input' => 'text',
                'required' => false,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
            ]
        );

        // Meta Keywords
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'landing_meta_keywords',
            [
                'type' => 'varchar',
                'label' => 'Category Landing Page Meta Keywords',
                'input' => 'text',
                'required' => false,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
            ]
        );

        // Meta Description
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'landing_meta_description',
            [
                'type' => 'varchar',
                'label' => 'Category Landing Page Meta Description',
                'input' => 'text',
                'required' => false,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
            ]
        );
    }
}