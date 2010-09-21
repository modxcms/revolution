<?php
/**
 * Loads the policy management page
 *
 * @package modx
 * @subpackage manager.security.access.policy
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('access_denied'));

if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('access_policy_err_ns'));
$policy = $modx->getObject('modAccessPolicy',$_REQUEST['id']);
if (empty($policy)) return $modx->error->failure($modx->lexicon('access_policy_err_nf'));

$modx->smarty->assign('policy',$policy);

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.panel.access.policy.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/security/access/policy/update.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-access-policy"
        ,policy: "'.$policy->get('id').'"
    });
});
// ]]>
</script>');

$this->checkFormCustomizationRules($policy);
return $modx->smarty->fetch('security/access/policy/update.tpl');