<?php
/**
 * Loads form customization
 *
 * @package modx
 * @subpackage manager.security.forms
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/fc/modx.grid.fcprofile.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/fc/list.js');

$modx->smarty->assign('_pagetitle',$modx->lexicon('form_customization'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('security/forms/index.tpl');