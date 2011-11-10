<?php
/**
 * Loads the policy management page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityAccessPolicyUpdateManagerController extends modManagerController {
    public $policyArray = array();
    
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('policy_edit');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.panel.access.policy.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/security/access/policy/update.js');
        $this->addHtml('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-access-policy"
                ,policy: "'.$this->policyArray['id'].'"
                ,record: '.$this->modx->toJSON($this->policyArray).'
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
        
        if (empty($scriptProperties['id'])) return $this->failure($this->modx->lexicon('access_policy_err_ns'));
        $policy = $this->modx->getObject('modAccessPolicy',$scriptProperties['id']);
        if (empty($policy)) return $this->failure($this->modx->lexicon('access_policy_err_nf'));
        $placeholders['policy'] = $policy;

        /* setup policy array */
        $this->policyArray = $policy->get(array(
            'id',
            'name',
            'description',
            'lexicon',
            'class',
            'template',
            'parent',
        ));
        $this->policyArray['permissions'] = $policy->getPermissions();
        $placeholders['policy'] = $this->policyArray;

        return $placeholders;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('policy').': '.$this->policyArray['name'];
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'security/access/policy/update.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('user','access','policy','context');
    }
}