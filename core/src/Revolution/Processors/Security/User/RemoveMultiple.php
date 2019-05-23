<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\User;

/**
 * Remove multiple users
 * @param string $users Comma-separated users ids
 * @package MODX\Revolution\Processors\Security\User
 */
class RemoveMultiple extends Delete
{
    public $users = [];

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        $users = $this->getProperty('users', '');
        if (empty($users)) {
            return $this->modx->lexicon('user_err_ns');
        }
        $this->users = explode(',', $users);

        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
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
