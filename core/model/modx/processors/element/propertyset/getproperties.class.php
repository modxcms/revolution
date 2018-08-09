<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

include_once dirname(__FILE__).'/get.class.php';
/**
 * Gets properties for a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
class modPropertySetGetPropertiesProcessor extends modPropertySetGetProcessor {
    /** @var string The ID of an element in modElementPropertySet key */
    public $elementKey = 'element';
    /** @var string The class of an element in modElementPropertySet key */
    public $element_class = 'element_class';

    /**
     * No need to do something here
     */
    public function beforeCleanup() {}

    /**
     * Output properties instead of property set
     * @return array|string
     */
    public function cleanup() {
        return $this->success('', $this->props);
    }
}

return 'modPropertySetGetPropertiesProcessor';
