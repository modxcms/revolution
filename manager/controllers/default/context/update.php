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


/* invoke OnContextFormPrerender event */
$onContextFormPrerender = $modx->invokeEvent('OnContextFormPrerender',array(
    'key' => $context->get('key'),
    'context' => &$context,
    'mode' => 'upd',
));
if (is_array($onContextFormPrerender)) $onContextFormPrerender = implode('',$onContextFormPrerender);
$modx->smarty->assign('OnContextFormPrerender',$onContextFormPrerender);

/* invoke OnContextFormRender event */
$onContextFormRender = $modx->invokeEvent('OnContextFormRender',array(
    'key' => $context->get('key'),
    'context' => &$context,
    'mode' => 'upd',
));
if (is_array($onContextFormRender)) $onContextFormRender = implode('',$onContextFormRender);
$onContextFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$onContextFormRender);
$modx->smarty->assign('OnContextFormRender',$onContextFormRender);


/*  assign context to smarty and display */
$modx->smarty->assign('context', $context);

/* register JS scripts */
$modx->smarty->assign('_ctx',$context->get('key'));
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.grid.access.context.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.settings.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.grid.context.settings.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.panel.context.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/context/update.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
// <![CDATA[
MODx.onContextFormRender = "'.$onContextFormRender.'";
MODx.ctx = "'.$context->get('key').'";
// ]]>
</script>');

$modx->smarty->assign('_pagetitle',$modx->lexicon('context').': '.$context->get('key'));
$this->checkFormCustomizationRules($context);
return $modx->smarty->fetch('context/update.tpl');