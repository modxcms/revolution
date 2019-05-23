<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Group\Setting;

use MODX\Revolution\modAccessibleObject;
use MODX\Revolution\modUserGroupSetting;

/**
 * Update a user group setting
 * @param integer $fk The group ID to create the setting for
 * @param string $key The setting key
 * @param string $value The setting value
 * @package MODX\Revolution\Processors\Security\Group\Setting
 */
class Update extends \MODX\Revolution\Processors\System\Settings\Update
{
    public $classKey = modUserGroupSetting::class;
    public $languageTopics = ['setting', 'user'];
    public $permission = ['usergroup_save' => true, 'settings' => true];

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        $group = (int)$this->getProperty('fk', 0);
        if (!$group) {
            return $this->modx->lexicon('user_group_err_ns');
        }

        $key = $this->getProperty('key', '');
        if (empty($key)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        $this->object = $this->modx->getObject($this->classKey, ['key' => $key, 'group' => $group]);

        if (!$this->object) {
            return $this->modx->lexicon($this->objectType . '_err_nf');
        }

        if ($this->checkSavePermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('save')) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }
}
