<?php
/**
 * Loads the workspace manager
 *
 * @package modx
 * @subpackage manager.workspaces
 */
if (!$modx->hasPermission('workspaces')) return $modx->error->failure($modx->lexicon('access_denied'));

/* ensure directories for Package Management are created */
$cacheManager = $modx->getCacheManager();
$directoryOptions = array(
    'new_folder_permissions' => $modx->getOption('new_folder_permissions',null,0775),
);

$errors = array();
/* create assets/ */
$assetsPath = $modx->getOption('base_path').'assets/';
if (!is_dir($assetsPath)) {
    $cacheManager->writeTree($assetsPath,$directoryOptions);
}
if (!is_dir($assetsPath) || !is_writable($assetsPath)) {
    $errors['assets_not_created'] = $modx->lexicon('dir_err_assets',array('path' => $assetsPath));
}
unset($assetsPath);

/* create assets/components/ */
$assetsCompPath = $modx->getOption('base_path').'assets/components/';
if (!is_dir($assetsCompPath)) {
    $cacheManager->writeTree($assetsCompPath,$directoryOptions);
}
if (!is_dir($assetsCompPath) || !is_writable($assetsCompPath)) {
    $errors['assets_comp_not_created'] = $modx->lexicon('dir_err_assets_comp',array('path' => $assetsCompPath));
}
unset($assetsCompPath);

/* create core/components/ */
$coreCompPath = $modx->getOption('core_path').'components/';
if (!is_dir($coreCompPath)) {
    $cacheManager->writeTree($coreCompPath,$directoryOptions);
}
if (!is_dir($coreCompPath) || !is_writable($coreCompPath)) {
    $errors['core_comp_not_created'] = $modx->lexicon('dir_err_core_comp',array('path' => $coreCompPath));
}
unset($coreCompPath);


if (!empty($errors)) {
    $modx->smarty->assign('errors',$errors);  
    return $modx->smarty->fetch('workspaces/error.tpl');
}


/* get default provider */
$provider = $modx->getObject('transport.modTransportProvider',array(
    'name' => 'modxcms.com',
));
if ($provider) {
    $modx->regClientStartupHTMLBlock('<script type="text/javascript">
MODx.provider = "'.$provider->get('id').'";
</script>');
}

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/core/modx.view.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.tree.checkbox.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.panel.wizard.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package.browser.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package.download.panel.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package.add.panel.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package.install.window.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package.uninstall.window.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package.update.window.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/combos.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package.grid.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/provider.grid.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/workspace.panel.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/index.js');

return $modx->smarty->fetch('workspaces/index.tpl');