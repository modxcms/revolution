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


use MODX\Revolution\modProcessor;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserProfile;

/**
 * Update a user profile
 * @package MODX\Revolution\Processors\Security\Profile
 */
class Update extends modProcessor
{
    /** @var modUserProfile $profile */
    public $profile;

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
        $this->prepare();

        /* save profile */
        if ($this->profile->save() === false) {
            return $this->failure($this->modx->lexicon('user_profile_err_save'));
        }

        /* log manager action */
        $this->modx->logManagerAction('save_profile', modUser::class, $this->modx->user->get('id'));

        return $this->success($this->modx->lexicon('success'), $this->profile->toArray());
    }

    public function prepare()
    {
        $properties = $this->getProperties();

        /* format and set data */
        $dob = $this->getProperty('dob');
        if (!empty($dob)) {
            $properties['dob'] = strtotime($dob);
        }
        $this->profile->fromArray($properties);
    }
}
