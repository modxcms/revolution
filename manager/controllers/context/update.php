<?php
/**
 * Loads the view context preview page.
 *
 * @package modx
 * @subpackage manager.context
 */
if(!$modx->hasPermission('edit_context')) return $modx->error->failure($modx->lexicon('access_denied'));

/* get context by key */
$context= $modx->getObjectGraph('modContext', '{"ContextSettings":{}}', $_REQUEST['key']);
if ($context == null) {
    return $modx->error->failure(sprintf($modx->lexicon('context_with_key_not_found'), $_REQUEST['key']));
}
if (!$context->checkPolicy(array('view' => true, 'save' => true))) return $modx->error->failure($modx->lexicon('permission_denied'));

/* prepare context data for display */
if (!$context->prepare()) {
    return $modx->error->failure($modx->lexicon('context_err_load_data'), $context->toArray());
}

/*  assign context to smarty and display */
$modx->smarty->assign('context', $context);

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.settings.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.grid.context.settings.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.panel.context.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/context/update.js');

return $modx->smarty->fetch('context/update.tpl');