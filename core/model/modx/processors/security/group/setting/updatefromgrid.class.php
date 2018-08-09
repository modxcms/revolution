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
 * Update a user group setting from a grid
 *
 * @param integer $group The group to create the setting for
 * @param string $key The setting key
 * @param string $value The setting value
 *
 * @package modx
 * @subpackage processors.security.group.setting
 */
class modUserGroupSettingUpdateFromGridProcessor extends modUserGroupSettingUpdateProcessor {
    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $properties = $this->modx->fromJSON($data);
        $this->setProperties($properties);
        $this->unsetProperty('data');
        $this->setDefaultProperties(array(
            'fk' => $this->getProperty('group')
        ));

        return parent::initialize();
    }
}

return 'modUserGroupSettingUpdateFromGridProcessor';
