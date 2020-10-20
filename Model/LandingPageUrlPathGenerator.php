<?php

namespace EricMorand\CategoryLandingPage\Model;

use Magento\Catalog\Model\Category;

class LandingPageUrlPathGenerator
{
    /** @var \Magento\Framework\Filter\FilterManager */
    protected $_filterManager;

    public function __construct(
        \Magento\Framework\Filter\FilterManager $filterManager
    ) {
        $this->_filterManager = $filterManager;
    }

    /**
     * @param Category $category
     *
     * @return string
     */
    public function getUrlPath(Category $category)
    {
        return $category->getData('landing_url_key');
    }

    /**
     * @param Category $category
     * @return string
     */
    public function getCanonicalUrlPath(Category $category)
    {
        return 'catalog/category/landing/id/' . $category->getId();
    }

    /**
     * @param Category $category
     * @return string
     */
    public function generateUrlKey(Category $category)
    {
        $urlKey = $this->getUrlPath($category);

        return $this->_filterManager->translitUrl($urlKey === '' || $urlKey === null ? $category->getName() : $urlKey);
    }
}
