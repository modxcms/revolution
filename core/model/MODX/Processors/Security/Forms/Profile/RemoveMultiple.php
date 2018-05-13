<?php

namespace MODX\Processors\Security\Forms\Profile;

use MODX\modFormCustomizationProfile;
use MODX\MODX;
use MODX\Processors\modProcessor;

/**
 * Remove multiple FC profiles
 *
 * @package modx
 * @subpackage processors.security.forms.profiles
 */
class RemoveMultiple extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('customize_forms');
    }


    public function getLanguageTopics()
    {
        return ['formcustomization'];
    }


    public function process()
    {
        $profiles = $this->getProperty('profiles');
        if (empty($profiles)) {
            return $this->failure($this->modx->lexicon('profile_err_ns'));
        }
        $profileIds = explode(',', $profiles);

        foreach ($profileIds as $profileId) {
            /** @var modFormCustomizationProfile $profile */
            $profile = $this->modx->getObject('modFormCustomizationProfile', $profileId);
            if ($profile) {
                if ($profile->remove() === false) {
                    $this->modx->log(MODX::LOG_LEVEL_ERROR, $this->modx->lexicon('profile_err_remove'));
                }
            }
        }

        return $this->success();
    }
}