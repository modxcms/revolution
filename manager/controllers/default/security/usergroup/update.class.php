<?php
/**
 * Loads the usergroup update page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityUserGroupUpdateManagerController extends modManagerController {
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('access_permissions');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.context.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.resource.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.category.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.source.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.panel.user.group.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/security/usergroup/update.js');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        $placeholders = array();
        if (empty($scriptProperties['id'])) {
            $this->usergroup = $this->modx->newObject('modUserGroup');
            $this->usergroup->set('id',0);
            $this->usergroup->set('name',$this->modx->lexicon('anonymous'));
        } else {
            $this->usergroup = $this->modx->getObject('modUserGroup',$scriptProperties['id']);
            if (empty($this->usergroup)) {
                $this->failure($this->modx->lexicon('usergroup_err_nf'));
            }
        }
        return $placeholders;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        $ugName = $this->usergroup ? $this->usergroup->get('name') : $this->modx->lexicon('anonymous');
        return $this->modx->lexicon('user_group').': '.$ugName;
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'security/usergroup/update.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('user','access','policy','context');
    }
}
