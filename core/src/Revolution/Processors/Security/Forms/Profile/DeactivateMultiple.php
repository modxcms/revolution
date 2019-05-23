<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Forms\Profile;

/**
 * Deactivate multiple FC Profiles
 * @package MODX\Revolution\Processors\Security\Forms\Profile
 */
class DeactivateMultiple extends Deactivate
{
    public $profiles = [];

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        $profiles = $this->getProperty('profiles', '');
        if (empty($profiles)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }
        $this->profiles = explode(',', $profiles);

        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        foreach ($this->profiles as $profile) {
            $this->setProperty('id', $profile);
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
