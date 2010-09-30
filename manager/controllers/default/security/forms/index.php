<?php
/**
 * Loads form customization
 *
 * @package modx
 * @subpackage manager.security.forms
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.grid.actiondom.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.panel.actiondom.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/security/forms/list.js');

$this->checkFormCustomizationRules();
return $modx->smarty->fetch('security/forms/index.tpl');