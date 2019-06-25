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

use MODX\Revolution\modUserGroupSetting;

/**
 * Create a User Group setting
 * @param integer $group/$fk The group to create the setting for
 * @param string $key The setting key
 * @param string $value The value of the setting
 * @param string $name The lexicon name for the setting
 * @param string $description The lexicon description for the setting
 * @param string $area The area for the setting
 * @param string $namespace The namespace for the setting
 * @package MODX\Revolution\Processors\Security\Group\Setting
 */
class Create extends \MODX\Revolution\Processors\System\Settings\Create
{
    public $classKey = modUserGroupSetting::class;
    public $languageTopics = ['setting', 'namespace', 'user'];

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $group = (int)$this->getProperty('fk', $this->getProperty('group', 0));
        if (!$group) {
            $this->addFieldError('fk', $this->modx->lexicon('user_group_err_ns'));
        }
        $this->object->set('group', $group);

        return parent::beforeSave();
    }

    /**
     * Check to see if a Setting already exists with this key and user group
     * @return boolean
     */
    public function alreadyExists()
    {
        return $this->doesAlreadyExist([
            'key' => $this->object->get('key'),
            'group' => $this->object->get('group'),
        ]);
    }
}
