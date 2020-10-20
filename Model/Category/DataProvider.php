<?php

namespace EricMorand\CategoryLandingPage\Model\Category;

class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{
    protected function getFieldsMap()
    {
        $fieldsMap = parent::getFieldsMap();

        $designFieldsMap = array_merge(
            $fieldsMap['design'],
            [
                'landing_custom_use_parent_settings',
                'landing_custom_design',
                'landing_page_layout'
            ]
        );

        $fieldsMap['design'] = $designFieldsMap;

        return $fieldsMap;
    }
}