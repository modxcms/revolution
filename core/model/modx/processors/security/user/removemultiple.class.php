<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

include_once dirname(__FILE__).'/delete.class.php';
/**
 * Remove multiple users
 *
 * @param string $users Comma-separated users ids
 * @package modx
 * @subpackage processors.security.user
 */

class modUserRemoveMultipleProcessor extends modUserDeleteProcessor {
    public $users = array();

    public function initialize() {
        $users = $this->getProperty('users', '');
        if (empty($users)) {
            return $this->modx->lexicon('user_err_ns');
        }
        $this->users = explode(',', $users);

        return true;
    }

    public function process() {
        foreach ($this->users as $user) {
            $this->setProperty('id', $user);
            $initialized = parent::initialize();
            if ($initialized === true) {
                $o = parent::process();
                if (!$o['success']) {
                    return $o;
                }
            } else {
                return $this->failure($initialized);
            }
        }
        return $this->success();
    }
}

return 'modUserRemoveMultipleProcessor';
