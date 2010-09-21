<?php
/**
 * Loads content type management
 *
 * @package modx
 * @subpackage manager.system.contenttype
 */
if (!$modx->hasPermission('content_types')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.grid.content.type.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/system/content.type.js');

$this->checkFormCustomizationRules();
return $modx->smarty->fetch('system/contenttype/index.tpl');