<?php
/**
 * Load property set management page
 *
 * @package modx
 * @subpackage manager.element.propertyset
 */
if (!$modx->hasPermission('edit_propertyset')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.local.property.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.element.properties.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.property.set.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/element/propertyset/index.js');

/* display template */
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('element/propertyset/index.tpl');