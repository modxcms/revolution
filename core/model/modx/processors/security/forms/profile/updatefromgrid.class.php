<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once (dirname(__FILE__) . '/update.class.php');
/**
 * Update a FC Profile from grid
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
class modFormCustomizationProfileUpdateFromGridProcessor extends modFormCustomizationProfileUpdateProcessor {
    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $properties = $this->modx->fromJSON($data);
        $this->setProperties($properties);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}
return 'modFormCustomizationProfileUpdateFromGridProcessor';
