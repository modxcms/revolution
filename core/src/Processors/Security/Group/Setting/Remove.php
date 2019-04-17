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
 * Remove a user group setting and its lexicon strings
 * @param integer $group The group associated to the setting
 * @param string $key The setting key
 * @package MODX\Revolution\Processors\Security\Group\Setting
 */
class Remove extends \MODX\Revolution\Processors\System\Settings\Remove
{
    public $classKey = modUserGroupSetting::class;

    /**
     * @return bool
     */
    public function initialize()
    {
        $key = $this->getProperty('key', '');
        $group = (int)$this->getProperty('group', 0);

        if (empty($key) || !$group) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        $primaryKey = ['key' => $key, 'group' => $group];
        $this->object = $this->modx->getObject($this->classKey, $primaryKey);

        if (!$this->object) {
            return $this->modx->lexicon($this->objectType . '_err_nf');
        }

        if ($this->checkRemovePermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('remove')) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }
}
