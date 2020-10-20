<?php

namespace EricMorand\CategoryLandingPage\Model;

use \Magento\Framework\TranslateInterface;
use Magento\Catalog\Model\Category\Attribute\LayoutUpdateManager as CategoryLayoutManager;
use Magento\Catalog\Model\Product\Attribute\LayoutUpdateManager as ProductLayoutManager;

class Design extends \Magento\Catalog\Model\Design
{
    /** @var \Magento\Catalog\Model\CategoryFactory */
    protected $_categoryFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\View\DesignInterface $design,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = [],
        TranslateInterface $translator = null,
        ?CategoryLayoutManager $categoryLayoutManager = null,
        ?ProductLayoutManager $productLayoutManager = null
    )
    {
        $this->_categoryFactory = $categoryFactory;

        parent::__construct($context, $registry, $localeDate, $design, $resource, $resourceCollection, $data, $translator, $categoryLayoutManager, $productLayoutManager);
    }

    protected function _extractSettings($object)
    {
        $settings = parent::_extractSettings($object);

        $object = $this->_categoryFactory->create()->load($object->getId());

        $settings
            ->setData('landing_custom_design', $object->getData('landing_custom_design'))
            ->setData('landing_page_layout', $object->getData('landing_page_layout'))
        ;

        return $settings;
    }
}