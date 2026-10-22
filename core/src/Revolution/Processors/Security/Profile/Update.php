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

use MODX\Revolution\modUser;
use MODX\Revolution\modUserProfile;
use MODX\Revolution\Processors\Processor;
use PDOException;

/**
 * Update a user profile
 * @package MODX\Revolution\Processors\Security\Profile
 */
class Update extends Processor
{
    /** @var modUserProfile $profile */
    public $profile;

    private const USERNAME_MAX_LENGTH = 100;

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('change_profile');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['user'];
    }

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        $this->profile = $this->modx->user->getOne('Profile');
        if ($this->profile === null) {
            return $this->modx->lexicon('user_profile_err_not_found');
        }
        return true;
    }

    /**
     * {@inheritDoc}
     * @return array|string
     */
    public function process()
    {
        if (!$this->validate()) {
            return $this->failure();
        }

        $this->prepare();

        $pdo = $this->modx->pdo;
        $useTransaction = $pdo instanceof \PDO && $this->modx->user->isDirty('username');
        if ($useTransaction) {
            $pdo->beginTransaction();
        }

        try {
            /* save profile */
            if ($this->profile->save() === false) {
                if ($useTransaction) {
                    $pdo->rollBack();
                }
                return $this->failure($this->modx->lexicon('user_profile_err_save'));
            }

            /* save user if username was changed */
            if ($this->modx->user->isDirty('username') && $this->modx->user->save() === false) {
                if ($useTransaction) {
                    $pdo->rollBack();
                }
                if ($this->usernameAlreadyExists((string)$this->modx->user->get('username'))) {
                    $this->addFieldError('username', $this->modx->lexicon('user_err_already_exists'));
                    return $this->failure();
                }
                return $this->failure($this->modx->lexicon('user_profile_err_save'));
            }

            if ($useTransaction) {
                $pdo->commit();
            }
        } catch (PDOException $e) {
            if ($useTransaction && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if ($this->isUsernameUniqueViolation($e)) {
                $this->addFieldError('username', $this->modx->lexicon('user_err_already_exists'));
                return $this->failure();
            }
            $this->modx->log(\xPDO\xPDO::LOG_LEVEL_ERROR, $e->getMessage());
            return $this->failure($this->modx->lexicon('user_profile_err_save'));
        }

        /* log manager action */
        $this->modx->logManagerAction('save_profile', modUser::class, $this->modx->user->get('id'));

        /* Change password */
        if ($this->getProperty('newpassword') !== 'false') {
            $newPassword = $this->getProperty('password_new');
            $oldPassword = $this->getProperty('password_old');
            if (!$this->modx->user->changePassword($newPassword, $oldPassword)) {
                return $this->failure($this->modx->lexicon('user_err_password_invalid_old'));
            }

            $this->modx->logManagerAction('change_profile_password', modUser::class, $this->modx->user->get('id'));

            return $this->success($this->modx->lexicon('user_password_changed', [
                'password' => $this->getProperty('password_new')
            ]));
        }

        $data = $this->profile->toArray();
        $data['username'] = $this->modx->user->get('username');

        return $this->success($this->modx->lexicon('success'), $data);
    }

    public function prepare()
    {
        $properties = $this->getProperties();

        /* username is on modUser, not modUserProfile: set on user and exclude from profile */
        if (array_key_exists('username', $properties) && !$this->hasErrors()) {
            $username = $properties['username'];
            if (is_string($username) && $username !== '') {
                $this->modx->user->set('username', $username);
            }
        }
        unset($properties['username']);

        /* format and set data */
        $dob = $this->getProperty('dob');
        if (!empty($dob)) {
            $dateFormat = $this->modx->getOption('manager_date_format', null, 'Y-m-d', true);
            $date = \DateTimeImmutable::createFromFormat($dateFormat, $dob);
            if ($date === false) {
                $this->addFieldError('dob', $this->modx->lexicon('user_err_not_specified_dob'));
            } else {
                $properties['dob'] = $date->getTimestamp();
            }
        }

        $this->profile->fromArray($properties);
    }

    public function validate()
    {
        $this->validateUsername();

        if ($this->getProperty('newpassword') !== 'false') {
            $oldPassword = $this->getProperty('password_old');
            $newPassword = $this->getProperty('password_new');
            $confirmPassword = $this->getProperty('password_confirm');

            /* if changing the password */
            if (!$this->modx->user->passwordMatches($oldPassword)) {
                $this->addFieldError('password_old', $this->modx->lexicon('user_err_password_invalid_old'));
            }
            if (empty($newPassword) || strlen($newPassword) < $this->modx->getOption('password_min_length', null, 12)) {
                $this->addFieldError('password_new', $this->modx->lexicon('user_err_password_too_short'));
            } elseif (!preg_match('/^[^\'\x3c\x3e\(\);\x22\x7b\x7d\x2f\x5c]+$/', $newPassword)) {
                $this->addFieldError('password_new', $this->modx->lexicon('user_err_password_invalid'));
            }
            if (empty($confirmPassword) || strcmp($newPassword, $confirmPassword) != 0) {
                $this->addFieldError('password_confirm', $this->modx->lexicon('user_err_password_no_match'));
            }
        }
        return !$this->hasErrors();
    }

    /**
     * Validate username when the field is submitted (same rules as User Validation::checkUsername).
     */
    private function validateUsername()
    {
        $properties = $this->getProperties();
        if (!array_key_exists('username', $properties)) {
            return;
        }

        $username = $properties['username'];
        if (!is_string($username) || trim($username) === '') {
            $this->addFieldError('username', $this->modx->lexicon('user_err_not_specified_username'));
            return;
        }
        if (strlen($username) > self::USERNAME_MAX_LENGTH) {
            $this->addFieldError('username', $this->modx->lexicon('user_err_username_invalid'));
            return;
        }
        if (!preg_match('/^[^\'\\x3c\\x3e\\(\\);\\x22]+$/', $username)) {
            $this->addFieldError('username', $this->modx->lexicon('user_err_username_invalid'));
            return;
        }
        if ($this->usernameAlreadyExists($username)) {
            $this->addFieldError('username', $this->modx->lexicon('user_err_already_exists'));
        }
    }

    /**
     * @param string $username
     * @return bool
     */
    private function usernameAlreadyExists($username)
    {
        return $this->modx->getCount(modUser::class, [
            'username' => $username,
            'id:!=' => $this->modx->user->get('id'),
        ]) > 0;
    }

    private function isUsernameUniqueViolation(PDOException $e): bool
    {
        $message = $e->getMessage();
        return stripos($message, 'username') !== false
            && (stripos($message, 'Duplicate') !== false || (string)$e->getCode() === '23000');
    }
}
