<?php
/**
 * Loads the create user page
 *
 * @package modx
 * @subpackage manager.security.user
 */
if (!$modx->hasPermission('new_user')) return $modx->error->failure($modx->lexicon('access_denied'));

/* invoke OnUserFormPrerender event */
$onUserFormPrerender = $modx->invokeEvent('OnUserFormPrerender', array(
    'id' => 0,
    'mode' => 'new',
));
if (is_array($onUserFormPrerender))
	$onUserFormPrerender = implode('',$onUserFormPrerender);
$modx->smarty->assign('onUserFormPrerender',$onUserFormPrerender);

/* invoke onInterfaceSettingsRender event */
$onInterfaceSettingsRender = $modx->invokeEvent('OnInterfaceSettingsRender', array(
    'id' => 0,
    'mode' => 'new',
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
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
// <![CDATA[
MODx.onUserFormRender = "'.$onUserFormRender.'";
// ]]>
</script>');

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.grid.user.group.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.panel.user.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/security/user/create.js');

return $modx->smarty->fetch('security/user/create.tpl');