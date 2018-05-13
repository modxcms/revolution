<?php

namespace MODX\Processors\Security\User;

/**
 * Remove multiple users
 *
 * @param string $users Comma-separated users ids
 *
 * @package modx
 * @subpackage processors.security.user
 */

class RemoveMultiple extends Delete
{
    public $users = [];


    public function initialize()
    {
        $users = $this->getProperty('users', '');
        if (empty($users)) {
            return $this->modx->lexicon('user_err_ns');
        }
        $this->users = explode(',', $users);

        return true;
    }


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