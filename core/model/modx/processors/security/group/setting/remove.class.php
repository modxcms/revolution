<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

include_once MODX_CORE_PATH . 'model/modx/processors/system/settings/remove.class.php';
/**
 * Remove a user group setting and its lexicon strings
 *
 * @param integer $group The group associated to the setting
 * @param string $key The setting key
 *
 * @package modx
 * @subpackage processors.security.group.setting
 */
class modUserGroupSettingRemoveProcessor extends modSystemSettingsRemoveProcessor {
    public $classKey = 'modUserGroupSetting';

    public function initialize() {
        $key = $this->getProperty('key', '');
        $group = (int)$this->getProperty('group', 0);

        if (empty($key) || !$group) {
            return $this->modx->lexicon($this->objectType.'_err_ns');
        }

        $primaryKey = array(
            'key' => $key,
            'group' => $group,
        );
        $this->object = $this->modx->getObject($this->classKey, $primaryKey);

        if (!$this->object) {
            return $this->modx->lexicon($this->objectType.'_err_nf');
        }

        if ($this->checkRemovePermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('remove')) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }
}

return 'modUserGroupSettingRemoveProcessor';
