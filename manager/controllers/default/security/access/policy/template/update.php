<?php
/**
 * Loads the policy template page
 *
 * @package modx
 * @subpackage manager.security.access.policy.template
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('access_denied'));

if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('policy_template_err_ns'));
$template = $modx->getObject('modAccessPolicyTemplate',$_REQUEST['id']);
if (empty($template)) return $modx->error->failure($modx->lexicon('policy_template_err_nf'));

/* get permissions */
$templateArray = $template->toArray();
$c = $modx->newQuery('modAccessPermission');
$c->sortby('name','ASC');
$permissions = $template->getMany('Permissions',$c);
foreach ($permissions as $permission) {
    $desc = $permission->get('description');
    if (!empty($templateArray['lexicon'])) {
        if (strpos($templateArray['lexicon'],':') !== false) {
            $modx->lexicon->load($templateArray['lexicon']);
        } else {
            $modx->lexicon->load('core:'.$templateArray['lexicon']);
        }
        $desc = $modx->lexicon($desc);
    }
    $templateArray['permissions'][] = array(
        $permission->get('name'),
        $permission->get('description'),
        $desc,
        $permission->get('value'),
    );
}


$modx->smarty->assign('template',$template);

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.panel.access.policy.template.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/security/access/policy/template/update.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-access-policy-template"
        ,template: "'.$template->get('id').'"
        ,record: '.$modx->toJSON($templateArray).'
    });
});
// ]]>
</script>');

$modx->smarty->assign('_pagetitle',$modx->lexicon('policy_template').': '.$template->get('name'));
$this->checkFormCustomizationRules($template);
return $modx->smarty->fetch('security/access/policy/template/update.tpl');