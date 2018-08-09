<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

include_once MODX_CORE_PATH . 'model/modx/processors/system/settings/create.class.php';
/**
 * Create a user setting
 *
 * @param integer $user/$fk The user to create the setting for
 * @param string $key The setting key
 * @param string $value The value of the setting
 * @param string $name The lexicon name for the setting
 * @param string $description The lexicon description for the setting
 * @param string $area The area for the setting
 * @param string $namespace The namespace for the setting
 *
 * @package modx
 * @subpackage processors.context.setting
 */

class modUserSettingCreateProcessor extends modSystemSettingsCreateProcessor {
    public $classKey = 'modUserSetting';
    public $languageTopics = array('setting','namespace', 'user');

    public function beforeSave() {
        $user = (int)$this->getProperty('fk', $this->getProperty('user', 0));
        if (!$user) {
            $this->addFieldError('user', $this->modx->lexicon('user_err_ns'));
        }
        $this->setProperty('user', $user);
        $this->object->set('user', $user);
        return parent::beforeSave();
    }

    /**
     * Check to see if a Setting already exists with this key and user
     * @return boolean
     */
    public function alreadyExists() {
        return $this->doesAlreadyExist(array(
            'key' => $this->getProperty('key'),
            'user' => $this->getProperty('user'),
        ));
    }
}

return 'modUserSettingCreateProcessor';
