<?php
/**
 * Loads the Import Resources page
 *
 * @package modx
 * @subpackage manager.system.import
 */
if (!$modx->hasPermission('import_static')) return $modx->error->failure($modx->lexicon('access_denied'));

/* reg client css/js */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.tree.resource.simple.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.panel.import.resources.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/system/import/resource.js');

$modx->smarty->assign('_pagetitle',$modx->lexicon('import_site'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('system/import/index.tpl');