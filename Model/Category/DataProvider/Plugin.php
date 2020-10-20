<?php

namespace EricMorand\CategoryLandingPage\Model\Category\DataProvider;

use Magento\Catalog\Model\Category\DataProvider;

class Plugin
{
    /**
     * @param DataProvider $subject
     * @param array $result
     * @return array
     */
    public function afterGetFieldsMap($subject, $result) {
        $result = array_merge($result, [
            'landing_design' => [
                'landing_custom_use_parent_settings',
                'landing_custom_design',
                'landing_page_layout'
            ],
        ]);

        return $result;
    }
}