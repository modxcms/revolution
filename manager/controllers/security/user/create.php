<?php
/**
 * Loads the create user page
 *
 * @package modx
 * @subpackage manager.security.user
 */
if (!$modx->hasPermission('new_user')) return $modx->error->failure($modx->lexicon('access_denied'));

$user = $modx->newObject('modUser');

/* load Roles */
$roles = $modx->getCollection('modUserRole');
$modx->smarty->assign('roles',$roles);


/* invoke OnUserFormPrerender event */
$onUserFormPrerender = $modx->invokeEvent('OnUserFormPrerender', array('id' => 0));
if (is_array($onUserFormPrerender))
	$onUserFormPrerender = implode('',$onUserFormPrerender);
$modx->smarty->assign('onUserFormPrerender',$onUserFormPrerender);

$modx->smarty->assign('blockedmode',0);


/* include the country list language file */
$_country_lang = array();
include_once $modx->config['core_path'].'lexicon/country/en.inc.php';
if ($modx->config['manager_language'] != 'en' && file_exists($modx->config['core_path'].'lexicon/country/'.$modx->config['manager_language'].'.inc.php')) {
    include_once $modx->config['core_path'].'lexicon/country/'.$modx->config['manager_language'].'.inc.php';
}
$modx->smarty->assign('_country_lang',$_country_lang);


/* invoke onInterfaceSettingsRender event */
$onInterfaceSettingsRender = $modx->invokeEvent('OnInterfaceSettingsRender', array('id' => 0));
if (is_array($onInterfaceSettingsRender)) {
	$onInterfaceSettingsRender = implode('', $onInterfaceSettingsRender);
}
$modx->smarty->assign('onInterfaceSettingsRender',$onInterfaceSettingsRender);


/* load Access Permissions */
$groupsarray = array();
$usergroups = $modx->getCollection('modUserGroup');

/* retain selected doc groups between post */
if (is_array($_POST['user_groups'])) {
    foreach ($_POST['user_groups'] as $n => $v)
        $groupsarray[] = $v;
}
$modx->smarty->assign('usergroups',$usergroups);
$modx->smarty->assign('groupsarray',$groupsarray);

/* assign user to smarty */
$modx->smarty->assign('user',$user);

/* register JS scripts */
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/widgets/security/modx.grid.user.group.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/widgets/security/modx.panel.user.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/sections/security/user/create.js');

return $modx->smarty->fetch('security/user/create.tpl');