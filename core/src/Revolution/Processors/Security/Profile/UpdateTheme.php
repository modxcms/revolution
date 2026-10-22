<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Profile;

use MODX\Revolution\modUserSetting;
use MODX\Revolution\Processors\Processor;

/**
 * Updates the current user's manager theme preference (light/dark/system).
 *
 * @package MODX\Revolution\Processors\Security\Profile
 */
class UpdateTheme extends Processor
{
    private const KEY = 'manager_dark_mode';
    private const ALLOWED = ['light', 'dark', 'system'];

    public $permission = 'change_profile';

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission($this->permission);
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['topmenu', 'user'];
    }

    /**
     * @return array|string|null
     */
    public function process()
    {
        $value = trim((string) $this->getProperty('value', ''));

        if (!in_array($value, self::ALLOWED, true)) {
            return $this->failure($this->modx->lexicon('theme_err_invalid_value'));
        }

        $userId = (int) $this->modx->user->get('id');
        $setting = $this->modx->getObject(modUserSetting::class, [
            'key' => self::KEY,
            'user' => $userId,
        ]);

        if ($setting === null) {
            $setting = $this->modx->newObject(modUserSetting::class);
            $setting->set('user', $userId);
            $setting->set('key', self::KEY);
            $setting->set('namespace', 'core');
            $setting->set('area', 'manager');
        }

        $setting->set('value', $value);

        if ($setting->save() === false) {
            return $this->failure($this->modx->lexicon('user_setting_err_save'));
        }

        $this->modx->invalidateUserConfigCache($this->modx->context->get('key'));

        return $this->success('', ['value' => $value]);
    }
}
