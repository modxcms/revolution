<?php
/**
 * Change a user's password
 *
 * @param boolean $password_reset If true, will reset the password to new
 * parameters
 * @param string $password_old The old password
 * @param string $password_new The new password
 * @param string $password_confirm A confirmed version of the new password
 *
 * @package modx
 * @subpackage processors.security.profile
 */
class modProfileChangePasswordProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('change_password');
    }
    public function getLanguageTopics() {
        return array('user');
    }

    public function process() {
        if (!$this->validate()) {
            return $this->failure();
        }

        if (!$this->modx->user->changePassword($this->getProperty('password_new'),$this->getProperty('password_old'))) {
            return $this->failure($this->modx->lexicon('user_err_password_invalid_old'));
        }

        /* log manager action */
        $this->logManagerAction();
        
        return $this->success($this->modx->lexicon('user_password_changed',array(
            'password' => $this->getProperty('password_method_screen')
                ? $this->getProperty('password_new')
                : str_repeat('*', rand((integer)$this->modx->getOption('password_min_length',null,8), strlen($this->getProperty('password_new')) * 2)),
        )));
    }

    public function validate() {
        $oldPassword = $this->getProperty('password_old');
        $newPassword = $this->getProperty('password_new');
        $confirmPassword = $this->getProperty('password_confirm');

        /* if changing the password */
        if (!$this->modx->user->passwordMatches($oldPassword)) {
            $this->addFieldError('password_old',$this->modx->lexicon('user_err_password_invalid_old'));
        }

        if (empty($newPassword) || strlen($newPassword) < $this->modx->getOption('password_min_length',null,8)) {
            $this->addFieldError('password_new',$this->modx->lexicon('user_err_password_too_short'));
        } else if (!preg_match('/^[^\'\\x3c\\x3e\\(\\);\\x22]+$/',$newPassword)) {
            $this->addFieldError('password_new',$this->modx->lexicon('user_err_password_invalid'));
        }
        
        if (empty($confirmPassword) || strcmp($newPassword,$confirmPassword) != 0) {
            $this->addFieldError('password_confirm',$this->modx->lexicon('user_err_password_no_match'));
        }
        return !$this->hasErrors();
    }

    public function logManagerAction() {
        $this->modx->logManagerAction('change_profile_password','modUser',$this->modx->user->get('id'));
    }
}
return 'modProfileChangePasswordProcessor';
