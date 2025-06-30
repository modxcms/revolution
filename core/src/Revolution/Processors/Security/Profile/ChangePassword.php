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


use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modUser;

/**
 * Change a user's password
 * @param boolean $password_reset If true, will reset the password to new parameters
 * @param string $password_old The old password
 * @param string $password_new The new password
 * @param string $password_confirm A confirmed version of the new password
 * @package MODX\Revolution\Processors\Security\Profile
 */
class ChangePassword extends Processor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('change_password');
    }

    public function getLanguageTopics()
    {
        return ['user'];
    }

    public function process()
    {
        if (!$this->validate()) {
            return $this->failure();
        }

        if (!$this->modx->user->changePassword($this->getProperty('password_new'),
            $this->getProperty('password_old'))) {
            return $this->failure($this->modx->lexicon('user_err_password_invalid_old'));
        }

        /* log manager action */
        $this->logManagerAction();

        $password = $this->getProperty('password_new');
        if (!$this->getProperty('password_method_screen')) {
            $length = (integer)$this->modx->getOption('password_min_length', null, 8);
            $password = str_repeat('*', mt_rand($length, strlen($this->getProperty('password_new')) * 2));
        }

        return $this->success($this->modx->lexicon('user_password_changed', ['password' => $password]));
    }

    public function validate()
    {
        $oldPassword = $this->getProperty('password_old');
        $newPassword = $this->getProperty('password_new');
        $confirmPassword = $this->getProperty('password_confirm');

        /* if changing the password */
        if (!$this->modx->user->passwordMatches($oldPassword)) {
            $this->addFieldError('password_old', $this->modx->lexicon('user_err_password_invalid_old'));
        }

        if (empty($newPassword) || strlen($newPassword) < $this->modx->getOption('password_min_length', null, 8)) {
            $this->addFieldError('password_new', $this->modx->lexicon('user_err_password_too_short'));
        } else {
            if (!preg_match('/^[^\'\x3c\x3e\(\);\x22\x7b\x7d\x2f\x5c]+$/', $newPassword)) {
                $this->addFieldError('password_new', $this->modx->lexicon('user_err_password_invalid'));
            }
        }

        if (empty($confirmPassword) || strcmp($newPassword, $confirmPassword) !== 0) {
            $this->addFieldError('password_confirm', $this->modx->lexicon('user_err_password_no_match'));
        }
        return !$this->hasErrors();
    }

    public function logManagerAction()
    {
        $this->modx->logManagerAction('change_profile_password', modUser::class, $this->modx->user->get('id'));
    }
}
