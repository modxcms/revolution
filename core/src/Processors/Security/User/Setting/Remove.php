<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\User\Setting;

use MODX\Revolution\modAccessibleObject;
use MODX\Revolution\modUserSetting;

/**
 * Remove a user setting and its lexicon strings
 * @param integer $user The user associated to the setting
 * @param string $key The setting key
 * @package MODX\Revolution\Processors\Security\User\Setting
 */
class Remove extends \MODX\Revolution\Processors\System\Settings\Remove
{
    public $classKey = modUserSetting::class;

    /**
     * @return bool
     */
    public function initialize()
    {
        $key = $this->getProperty('key', '');
        $user = (int)$this->getProperty('user', 0);

        if (empty($key) || !$user) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        $primaryKey = ['key' => $key, 'user' => $user];
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
