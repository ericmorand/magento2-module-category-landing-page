<?php

declare(strict_types=1);

namespace EricMorand\CategoryLandingPage\Plugin\Catalog\Model\ResourceModel;

use EricMorand\CategoryLandingPage\Model\LandingPageUrlPathGenerator;
use EricMorand\CategoryLandingPage\Model\LandingPageUrlRewriteGenerator;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Before save and around delete plugin for \Magento\Catalog\Model\ResourceModel\Category
 *
 * - generate landing_url_key if the merchant didn't fill this field
 * - remove all url rewrites for landing pages on delete
 */
class Category
{
    /** @var LandingPageUrlPathGenerator */
    protected $_landingPageUrlPathGenerator;

    /**
     * @var UrlPersistInterface
     */
    protected $_urlPersist;

    public function __construct(
        LandingPageUrlPathGenerator $landingPagePathGenerator,
        UrlPersistInterface $urlPersist
    ) {
        $this->_landingPageUrlPathGenerator = $landingPagePathGenerator;
        $this->_urlPersist = $urlPersist;
    }

    public function beforeSave(
        \Magento\Catalog\Model\ResourceModel\Category $subject,
        \Magento\Catalog\Model\Category $object
    ) {
        $urlKey = $object->getData('landing_url_key');

        if ($urlKey === '' || $urlKey === null) {
            $object->setData('landing_url_key', $this->_landingPageUrlPathGenerator->generateUrlKey($object));
        }
    }

    public function afterDelete(
        \Magento\Catalog\Model\ResourceModel\Category $subject,
        AbstractDb $result,
        AbstractModel $category
    ) {
        if ($category->isDeleted()) {
            $this->_urlPersist->deleteByData(
                [
                    UrlRewrite::ENTITY_ID => $category->getId(),
                    UrlRewrite::ENTITY_TYPE => LandingPageUrlRewriteGenerator::ENTITY_TYPE,
                ]
            );
        }

        return $result;
    }
}
