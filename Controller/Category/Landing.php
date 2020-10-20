<?php

namespace EricMorand\CategoryLandingPage\Controller\Category;

use Magento\Catalog\Controller\Category\View;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Helper\Category as CategoryHelper;
use Magento\Catalog\Model\Design;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product\ProductList\ToolbarMemorizer;
use Magento\Catalog\Model\Session;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\Category\Attribute\LayoutUpdateManager;

class Landing extends View
{
    /** @var \Magento\Catalog\Model\CategoryFactory */
    protected $_categoryFactory;

    /** @var \EricMorand\CategoryLandingPage\Model\Design */
    protected $_landingPageDesign;

    public function __construct(
        Context $context,
        Design $catalogDesign,
        Session $catalogSession,
        Registry $coreRegistry,
        StoreManagerInterface $storeManager,
        CategoryUrlPathGenerator $categoryUrlPathGenerator,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \EricMorand\CategoryLandingPage\Model\Design $landingPageDesign,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        ToolbarMemorizer $toolbarMemorizer = null,
        ?LayoutUpdateManager $layoutUpdateManager = null,
        CategoryHelper $categoryHelper = null,
        LoggerInterface $logger = null)
    {
        parent::__construct($context, $catalogDesign, $catalogSession, $coreRegistry, $storeManager, $categoryUrlPathGenerator, $resultPageFactory, $resultForwardFactory, $layerResolver, $categoryRepository, $toolbarMemorizer, $layoutUpdateManager, $categoryHelper, $logger);

        $this->_landingPageDesign = $landingPageDesign;
        $this->_categoryFactory = $categoryFactory;
    }

    public function execute()
    {
        /** @var Category $category */
        $category = $this->_initCategory();

        if ($category) {
            $category = $this->_categoryFactory->create()->load($category->getId());

            $settings = $this->_landingPageDesign->getDesignSettings($category);

            // apply custom design
            if ($customDesign = $settings->getData('landing_custom_design')) {
                $this->_catalogDesign->applyCustomDesign($customDesign);
            }

            $page = $this->resultPageFactory->create();

            $pageConfig = $page->getConfig();

            // apply custom page layout
            if ($pageLayout = $settings->getData('landing_page_layout')) {
                $pageConfig->setPageLayout($pageLayout);
            }

            $pageConfig->getTitle()->set($category->getName());
            $pageConfig->setMetaTitle($category->getData('landing_meta_title'));
            $pageConfig->setKeywords($category->getData('landing_meta_keywords'));
            $pageConfig->setDescription($category->getData('landing_meta_description'));

            $this->_view->loadLayout();
            $this->_view->renderLayout();
        }
        else {
            return parent::execute();
        }
    }
}
