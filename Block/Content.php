<?php

namespace EricMorand\CategoryLandingPage\Block;

use GirardPerregaux\Catalog\Api\Data\CategoryInterface;
use Magento\Cms\Model\Block;
use Magento\Cms\Model\BlockRepository;
use Magento\Cms\Model\GetBlockByIdentifier;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class Content extends Template
{
    /** @var Registry */
    protected $_registry;

    /** @var BlockRepository */
    protected $_blockRepository;

    /** @var GetBlockByIdentifier */
    protected $_getBlockByIdentifier;

    /** @var \Magento\Cms\Model\Template\FilterProvider */
    protected $_filterProvider;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        BlockRepository $blockRepository,
        GetBlockByIdentifier $blockByIdentifier,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        array $data = []
    )
    {
        $this->_registry = $registry;
        $this->_blockRepository = $blockRepository;
        $this->_getBlockByIdentifier = $blockByIdentifier;
        $this->_filterProvider = $filterProvider;

        parent::__construct($context, $data);
    }

    protected function _toHtml()
    {
        /** @var CategoryInterface $category */
        $category = $this->_registry->registry('current_category');

        if ($blockId = $category->getData('landing_page')) {
            if ($block = $this->_blockRepository->getById($blockId)) {
                $storeId = $this->_storeManager->getStore()->getId();

                $block = $this->_getBlockByIdentifier->execute($block->getIdentifier(), $storeId);

                return $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($block->getContent());
            }
        }

        return '';
    }
}
