<?php
/**
 * Loads the policy template page
 *
 * @package modx
 * @subpackage manager.security.access.policy.template
 */
class SecurityAccessPolicyTemplateUpdateManagerController extends modManagerController {
    public $templateArray = array();

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
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/security/modx.panel.access.policy.template.js');
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/sections/security/access/policy/template/update.js');
        $this->modx->regClientStartupHTMLBlock('
        <script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-access-policy-template"
                ,template: "'.$this->templateArray['id'].'"
                ,record: '.$this->modx->toJSON($this->templateArray).'
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

        if (empty($scriptProperties['id'])) return $this->failure($this->modx->lexicon('policy_template_err_ns'));
        $template = $this->modx->getObject('modAccessPolicyTemplate',$scriptProperties['id']);
        if (empty($template)) return $this->failure($this->modx->lexicon('policy_template_err_nf'));

        /* get permissions */
        $this->templateArray = $template->toArray();
        $c = $this->modx->newQuery('modAccessPermission');
        $c->sortby('name','ASC');
        $permissions = $template->getMany('Permissions',$c);
        foreach ($permissions as $permission) {
            $desc = $permission->get('description');
            if (!empty($templateArray['lexicon'])) {
                if (strpos($templateArray['lexicon'],':') !== false) {
                    $this->modx->lexicon->load($templateArray['lexicon']);
                } else {
                    $this->modx->lexicon->load('core:'.$templateArray['lexicon']);
                }
                $desc = $this->modx->lexicon($desc);
            }
            $this->templateArray['permissions'][] = array(
                $permission->get('name'),
                $permission->get('description'),
                $desc,
                $permission->get('value'),
            );
        }


        $placeholders['template'] = $this->templateArray;

        return $placeholders;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('policy_template').': '.$this->templateArray['name'];
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'security/access/policy/template/update.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('user','access','policy','context');
    }
}