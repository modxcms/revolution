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
 * Updates a user setting
 * @param integer $fk The user ID to create the setting for
 * @param string $key The setting key
 * @param string $value The setting value
 * @package MODX\Revolution\Processors\Security\User\Setting
 */
class Update extends \MODX\Revolution\Processors\System\Settings\Update
{
    public $classKey = modUserSetting::class;
    public $languageTopics = ['setting', 'user'];
    public $permission = ['save_user' => true, 'settings' => true];

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        $user = (int)$this->getProperty('fk', 0);
        if (!$user) {
            return $this->modx->lexicon('user_err_ns');
        }

        $key = $this->getProperty('key', '');
        if (empty($key)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        $this->object = $this->modx->getObject($this->classKey, ['key' => $key, 'user' => $user]);

        if (!$this->object) {
            return $this->modx->lexicon($this->objectType . '_err_nf');
        }

        if ($this->checkSavePermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('save')) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }

}

