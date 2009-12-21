<?php
/**
 * Loads update user page
 *
 * @package modx
 * @subpackage manager.security.user
 */
if (!$modx->hasPermission('edit_user')) return $modx->error->failure($modx->lexicon('access_denied'));

/* get user */
if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
$user = $modx->getObject('modUser',$_REQUEST['id']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_nf'));

/* invoke OnUserFormPrerender event */
$onUserFormPrerender = $modx->invokeEvent('OnUserFormPrerender', array(
    'id' => $user->get('id'),
    'user' => &$user,
    'mode' => 'upd',
));
if (is_array($onUserFormPrerender)) {
	$onUserFormPrerender = implode('',$onUserFormPrerender);
}
$modx->smarty->assign('onUserFormPrerender',$onUserFormPrerender);

/* invoke onInterfaceSettingsRender event */
$onInterfaceSettingsRender = $modx->invokeEvent('OnInterfaceSettingsRender', array(
    'id' => $user->get('id'),
    'user' => &$user,
    'mode' => 'upd',
));
if (is_array($onInterfaceSettingsRender)) {
	$onInterfaceSettingsRender = implode('', $onInterfaceSettingsRender);
}
$modx->smarty->assign('onInterfaceSettingsRender',$onInterfaceSettingsRender);

/* invoke OnUserFormRender event */
$onUserFormRender = $modx->invokeEvent('OnUserFormRender', array(
    'id' => 0,
    'mode' => 'new',
));
if (is_array($onUserFormRender)) $onUserFormRender = implode('',$onUserFormRender);
$onUserFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$onUserFormRender);
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
// <![CDATA[
MODx.onUserFormRender = "'.$onUserFormRender.'";
// ]]>
</script>');

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.settings.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.grid.user.settings.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.grid.user.group.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.panel.user.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/security/user/update.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-user-update"
        ,user: "'.$user->get('id').'"
    });
});
// ]]>
</script>');

return $modx->smarty->fetch('security/user/update.tpl');