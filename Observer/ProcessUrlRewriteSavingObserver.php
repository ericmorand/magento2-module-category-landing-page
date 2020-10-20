<?php

namespace EricMorand\CategoryLandingPage\Observer;

use EricMorand\CategoryLandingPage\Model\LandingPageUrlRewriteGenerator;
use Magento\Catalog\Model\Category;
use Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator;
use Magento\Framework\Event\ObserverInterface;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Generates Category Landing Page Url Rewrites after save.
 */
class ProcessUrlRewriteSavingObserver implements ObserverInterface
{
    /** @var LandingPageUrlRewriteGenerator */
    protected $_urlRewriteGenerator;

    /** @var UrlPersistInterface */
    protected $_urlPersist;

    public function __construct(
        LandingPageUrlRewriteGenerator $urlRewriteGenerator,
        UrlPersistInterface $urlPersist
    )
    {
        $this->_urlRewriteGenerator = $urlRewriteGenerator;
        $this->_urlPersist = $urlPersist;
    }

    /**
     * Generate urls for UrlRewrite and save it in storage
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var Category $category */
        $category = $observer->getEvent()->getData('category');

        if ($category->getParentId() == Category::TREE_ROOT_ID) {
            return;
        }

        if ($category->dataHasChangedFor('landing_url_key')) {
            $urls = $this->_urlRewriteGenerator->generate($category);

            $this->_urlPersist->deleteByData([
                UrlRewrite::ENTITY_ID => $category->getId(),
                UrlRewrite::ENTITY_TYPE => LandingPageUrlRewriteGenerator::ENTITY_TYPE,
            ]);

            $this->_urlPersist->replace($urls);
        }
    }
}
