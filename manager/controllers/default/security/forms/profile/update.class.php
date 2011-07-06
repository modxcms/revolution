<?php
/**
 * Loads form customization profile editing panel
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityFormsProfileUpdateManagerController extends modManagerController {
    public $profileArray = array();
    
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
        $this->addHtml('<script type="text/javascript">
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
    public function process(array $scriptProperties = array()) {
        $placeholders = array();
        
        if (empty($scriptProperties['id'])) return $this->failure($this->modx->lexicon('profile_err_ns'));
        $profile = $this->modx->getObject('modFormCustomizationProfile',$scriptProperties['id']);
        if (empty($profile)) return $this->failure($this->modx->lexicon('profile_err_nfs',array('id' => $scriptProperties['id'])));

        $this->profileArray = $profile->toArray();

        $c = $this->modx->newQuery('modUserGroup');
        $c->innerJoin('modFormCustomizationProfileUserGroup','FormCustomizationProfiles');
        $c->where(array(
            'FormCustomizationProfiles.profile' => $profile->get('id'),
        ));
        $c->sortby('name','ASC');
        $usergroups = $this->modx->getCollection('modUserGroup',$c);

        $this->profileArray['usergroups'] = array();
        foreach ($usergroups as $usergroup) {
            $this->profileArray['usergroups'][] = array(
                $usergroup->get('id'),
                $usergroup->get('name'),
            );
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
        return $this->modx->lexicon('form_customization');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'security/forms/profile.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('user','access','policy','formcustomization');
    }
}