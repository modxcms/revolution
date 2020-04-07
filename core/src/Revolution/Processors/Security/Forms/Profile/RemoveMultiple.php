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

use MODX\Revolution\modFormCustomizationProfile;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modX;

/**
 * Remove multiple FC profiles
 * @package MODX\Revolution\Processors\Security\Forms\Profile
 */
class RemoveMultiple extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('customize_forms');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['formcustomization'];
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $profiles = $this->getProperty('profiles');
        if (empty($profiles)) {
            return $this->failure($this->modx->lexicon('profile_err_ns'));
        }
        $profileIds = explode(',', $profiles);

        foreach ($profileIds as $profileId) {
            /** @var modFormCustomizationProfile $profile */
            $profile = $this->modx->getObject(modFormCustomizationProfile::class, $profileId);
            if ($profile) {
                if ($profile->remove() === false) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('profile_err_remove'));
                }
            }
        }
        return $this->success();
    }
}
