<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modFormCustomizationProfile;
use MODX\Revolution\modFormCustomizationProfileUserGroup;
use MODX\Revolution\modManagerController;
use MODX\Revolution\modUserGroup;

/**
 * Loads form customization profile editing panel
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityFormsProfileUpdateManagerController extends modManagerController {
    public $profileArray = [];

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('customize_forms');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/fc/modx.fc.common.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/fc/modx.panel.fcprofile.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/fc/modx.grid.fcset.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/fc/profile/update.js');
        $this->addHtml('<script>
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-fc-profile-update"
                ,profile: "'.$this->profileArray['id'].'"
                ,record: '.$this->modx->toJSON($this->profileArray).'
            });
        });
        // ]]>
        </script>');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = []) {
        $placeholders = [];

        if (empty($scriptProperties['id']) || strlen($scriptProperties['id']) !== strlen((integer)$scriptProperties['id'])) {
            return $this->failure($this->modx->lexicon('profile_err_ns'));
        }
        $profile = $this->modx->getObject(modFormCustomizationProfile::class, ['id' => $scriptProperties['id']]);
        if (empty($profile)) return $this->failure($this->modx->lexicon('profile_err_nfs',
            ['id' => $scriptProperties['id']]));

        $this->profileArray = $profile->toArray();

        $c = $this->modx->newQuery(modUserGroup::class);
        $c->innerJoin(modFormCustomizationProfileUserGroup::class,'FormCustomizationProfiles');
        $c->where([
            'FormCustomizationProfiles.profile' => $profile->get('id'),
        ]);
        $c->sortby('name','ASC');
        $usergroups = $this->modx->getCollection(modUserGroup::class, $c);

        $this->profileArray['usergroups'] = [];
        foreach ($usergroups as $usergroup) {
            $this->profileArray['usergroups'][] = [
                $usergroup->get('id'),
                $usergroup->get('name'),
            ];
        }

        $placeholders['profile'] = $this->profileArray;

        return $placeholders;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('form_customization').': '.$this->profileArray['name'];
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return '';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return ['user','access','policy','formcustomization'];
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Form+Customization+Profiles';
    }
}
