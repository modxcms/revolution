<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Handles common validation for user processors
 *
 * @package modx
 */
class modUserValidation {
    /** @var modX $modx */
    public $modx;
    /** @var modUserCreateProcessor|modUserUpdateProcessor $processor */
    public $processor;
    /** @var modUser $user */
    public $user;
    /** @var modUserProfile $profile */
    public $profile;

    function __construct(modObjectProcessor &$processor,modUser &$user,modUserProfile &$profile) {
        $this->processor =& $processor;
        $this->modx =& $processor->modx;
        $this->user =& $user;
        $this->profile =& $profile;
    }

    public function validate() {
        $this->checkUsername();
        $this->checkPassword();
        $this->checkEmail();
        $this->checkPhone();
        $this->checkCellPhone();
        $this->checkBirthDate();
        $this->checkBlocked();

        return !$this->processor->hasErrors();
    }

    public function checkUsername() {
        $username = $this->processor->getProperty('username');
        if (empty($username)) {
            $this->processor->addFieldError('username',$this->modx->lexicon('user_err_not_specified_username'));
        } elseif (!preg_match('/^[^\'\\x3c\\x3e\\(\\);\\x22]+$/', $username)) {
            $this->processor->addFieldError('username',$this->modx->lexicon('user_err_username_invalid'));
        } else if (!empty($username)) {
            if ($this->alreadyExists($username)) {
                $this->processor->addFieldError('username',$this->modx->lexicon('user_err_already_exists'));
            }
            $this->user->set('username',$username);
        }
    }

    public function alreadyExists($name) {
        return $this->modx->getCount('modUser',array(
            'username' => $name,
            'id:!=' => $this->user->get('id'),
        )) > 0;
    }

    public function checkPassword() {
        $newPassword = $this->processor->getProperty('newpassword',null);
        $id = $this->processor->getProperty('id');
        if ($newPassword !== null && $newPassword != 'false' || empty($id)) {
            $passwordNotifyMethod = $this->processor->getProperty('passwordnotifymethod',null);
            if (empty($passwordNotifyMethod)) {
                $this->processor->addFieldError('password_notify_method',$this->modx->lexicon('user_err_not_specified_notification_method'));
            }
            $passwordGenerationMethod = $this->processor->getProperty('passwordgenmethod','g');
            if ($passwordGenerationMethod == 'g') {
                $autoPassword = $this->user->generatePassword();
                $this->user->set('password', $autoPassword);
                $this->processor->newPassword= $autoPassword;
            } else {
                $specifiedPassword = $this->processor->getProperty('specifiedpassword');
                $confirmPassword = $this->processor->getProperty('confirmpassword');
                if (empty($specifiedPassword)) {
                    $this->processor->addFieldError('specifiedpassword',$this->modx->lexicon('user_err_not_specified_password'));
                } elseif ($specifiedPassword != $confirmPassword) {
                    $this->processor->addFieldError('confirmpassword',$this->modx->lexicon('user_err_password_no_match'));
                } elseif (strlen($specifiedPassword) < $this->modx->getOption('password_min_length', null, 8, true)) {
                    $this->processor->addFieldError('specifiedpassword',$this->modx->lexicon('user_err_password_too_short'));
                } elseif (!preg_match('/^[^\'\x3c\x3e\(\);\x22\x7b\x7d\x2f\x5c]+$/', $specifiedPassword)) {
                    $this->processor->addFieldError('specifiedpassword', $this->modx->lexicon('user_err_password_invalid'));
                } else {
                    $this->user->set('password',$specifiedPassword);
                    $this->processor->newPassword = $specifiedPassword;
                }
            }
        }
        return $this->processor->newPassword;
    }

    public function checkEmail() {
        $email = $this->processor->getProperty('email');
        if (empty($email)) {
            $this->processor->addFieldError('email',$this->modx->lexicon('user_err_not_specified_email'));
        }

        if (!$this->modx->getOption('allow_multiple_emails',null,true)) {
            /** @var modUserProfile $emailExists */
            $emailExists = $this->modx->getObject('modUserProfile',array('email' => $email));
            if ($emailExists) {
                if ($emailExists->get('internalKey') != $this->processor->getProperty('id')) {
                    $this->processor->addFieldError('email',$this->modx->lexicon('user_err_already_exists_email'));
                }
            }
        }
        return $email;
    }

    public function checkPhone() {
        $phone = $this->processor->getProperty('phone');
        if (!empty($phone)) {
            if ($this->modx->getOption('clean_phone_number',null,false)) {
                $phone = str_replace(' ','',$phone);
                $phone = str_replace('-','',$phone);
                $phone = str_replace('(','',$phone);
                $phone = str_replace(')','',$phone);
                $phone = str_replace('+','',$phone);
                $this->processor->setProperty('phone',$phone);
                $this->profile->set('phone',$phone);
            }
        }
    }

    public function checkCellPhone() {
        $phone = $this->processor->getProperty('mobilephone');
        if (!empty($phone)) {
            if ($this->modx->getOption('clean_phone_number',null,false)) {
                $phone = str_replace(' ','',$phone);
                $phone = str_replace('-','',$phone);
                $phone = str_replace('(','',$phone);
                $phone = str_replace(')','',$phone);
                $phone = str_replace('+','',$phone);
                $this->processor->setProperty('mobilephone',$phone);
                $this->profile->set('mobilephone',$phone);
            }
        }
    }

    public function checkBirthDate() {
        $birthDate = $this->processor->getProperty('dob');
        if (!empty($birthDate)) {
            $birthDate = strtotime($birthDate);
            if (false === $birthDate) {
                $this->processor->addFieldError('dob',$this->modx->lexicon('user_err_not_specified_dob'));
            }
            $this->processor->setProperty('dob',$birthDate);
            $this->profile->set('dob',$birthDate);
        }
    }

    public function checkBlocked() {
        /* blocked until */
        $blockedUntil = $this->processor->getProperty('blockeduntil');
        if (!empty($blockedUntil)) {
            $blockedUntil = str_replace('-','/',$blockedUntil);
            if (!$blockedUntil = strtotime($blockedUntil)) {
                $this->processor->addFieldError('blockeduntil',$this->modx->lexicon('user_err_not_specified_blockeduntil'));
            }
            $this->processor->setProperty('blockeduntil',$blockedUntil);
            $this->profile->set('blockeduntil',$blockedUntil);
        }

        /* blocked after */
        $blockedAfter = $this->processor->getProperty('blockedafter');
        if (!empty($blockedAfter)) {
            $blockedAfter = str_replace('-','/',$blockedAfter);
            if (!$blockedAfter = strtotime($blockedAfter)) {
                $this->processor->addFieldError('blockedafter',$this->modx->lexicon('user_err_not_specified_blockedafter'));
            }
            $this->processor->setProperty('blockedafter',$blockedAfter);
            $this->profile->set('blockedafter',$blockedAfter);
        }
    }

}
