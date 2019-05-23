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

use MODX\Revolution\modUserSetting;

/**
 * Create a user setting
 * @param integer $user/$fk The user to create the setting for
 * @param string $key The setting key
 * @param string $value The value of the setting
 * @param string $name The lexicon name for the setting
 * @param string $description The lexicon description for the setting
 * @param string $area The area for the setting
 * @param string $namespace The namespace for the setting
 * @package MODX\Revolution\Processors\Security\User\Setting
 */
class Create extends \MODX\Revolution\Processors\System\Settings\Create
{
    public $classKey = modUserSetting::class;
    public $languageTopics = ['setting', 'namespace', 'user'];

    /**
     * @return bool
     */
    public function beforeSave()
    {
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
    public function alreadyExists()
    {
        return $this->doesAlreadyExist([
            'key' => $this->getProperty('key'),
            'user' => $this->getProperty('user'),
        ]);
    }
}
