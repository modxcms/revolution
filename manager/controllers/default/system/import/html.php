<?php
/**
 * Loads the Import by HTML page
 *
 * @package modx
 * @subpackage manager.system.import
 */
if (!$modx->hasPermission('import_static')) return $modx->error->failure($modx->lexicon('access_denied'));

/* reg client css/js */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.tree.resource.simple.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.panel.import.html.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/system/import/html.js');

$modx->smarty->assign('_pagetitle',$modx->lexicon('import_site_html'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('system/import/html.tpl');