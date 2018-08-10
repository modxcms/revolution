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
 * Remove multiple FC profiles
 *
 * @package modx
 * @subpackage processors.security.forms.profiles
 */
class modFormCustomizationProfileRemoveMultipleProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('customize_forms');
    }
    public function getLanguageTopics() {
        return array('formcustomization');
    }

    public function process() {
        $profiles = $this->getProperty('profiles');
        if (empty($profiles)) {
            return $this->failure($this->modx->lexicon('profile_err_ns'));
        }
        $profileIds = explode(',',$profiles);

        foreach ($profileIds as $profileId) {
            /** @var modFormCustomizationProfile $profile */
            $profile = $this->modx->getObject('modFormCustomizationProfile',$profileId);
            if ($profile) {
                if ($profile->remove() === false) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,$this->modx->lexicon('profile_err_remove'));
                }
            }
        }
        return $this->success();
    }
}
return 'modFormCustomizationProfileRemoveMultipleProcessor';
