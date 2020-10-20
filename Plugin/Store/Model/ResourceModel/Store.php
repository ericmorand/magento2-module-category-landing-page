<?php

declare(strict_types=1);

namespace EricMorand\CategoryLandingPage\Plugin\Store\Model\ResourceModel;

use EricMorand\CategoryLandingPage\Model\LandingPageUrlRewriteGenerator;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\ResourceModel\Store as ResourceStore;
use Magento\UrlRewrite\Model\UrlPersistInterface;

/**
 * Plugin which is listening store resource model and on save replace category landing page url rewrites.
 */
class Store
{
    /** @var UrlPersistInterface */
    protected $_urlPersist;

    /** @var LandingPageUrlRewriteGenerator */
    protected $_landingPageUrlRewriteGenerator;

    /** @var CategoryRepositoryInterface */
    protected $_categoryRepository;

    /** @var SearchCriteriaBuilder */
    protected $_searchCriteriaBuilder;

    public function __construct(
        UrlPersistInterface $urlPersist,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CategoryRepositoryInterface $categoryRepository,
        LandingPageUrlRewriteGenerator $landingPageUrlRewriteGenerator
    ) {
        $this->_urlPersist = $urlPersist;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_categoryRepository = $categoryRepository;
        $this->_landingPageUrlRewriteGenerator = $landingPageUrlRewriteGenerator;
    }

    public function afterSave(ResourceStore $object, ResourceStore $result, AbstractModel $store): void
    {
        if ($store->isObjectNew() || $store->dataHasChangedFor('group_id')) {
            $this->_urlPersist->replace(
                $this->generateLandingPagesUrls((int)$store->getId())
            );
        }
    }

    protected function generateLandingPagesUrls(int $storeId): array
    {
        $rewrites = [];
        $urls = [];
        $searchCriteria = $this->_searchCriteriaBuilder->create();
        $categoryCollection = $this->_categoryRepository->getList($searchCriteria)->getItems();

        foreach ($categoryCollection as $category) {
            $rewrites[] = $this->_landingPageUrlRewriteGenerator->generate($category, $storeId);
        }

        $urls = array_merge($urls, ...$rewrites);

        return $urls;
    }
}
