<?php
/**
 * Load create snippet page
 *
 * @package modx
 * @subpackage manager.element.snippet
 */
if (!$modx->hasPermission('new_snippet')) return $modx->error->failure($modx->lexicon('access_denied'));

/* preset category if specified */
if (isset($_REQUEST['category'])) {
	$category = $modx->getObject('modCategory',$_REQUEST['category']);
	if ($category != null) $modx->smarty->assign('category',$category);
} else { $category = null; }

/* invoke onSnipFormRender event */
$onSnipFormRender = $modx->invokeEvent('OnSnipFormRender',array(
    'id' => 0,
    'mode' => modSystemEvent::MODE_NEW,
));
if (is_array($onSnipFormRender)) $onSnipFormRender = implode('',$onSnipFormRender);
$onSnipFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$onSnipFormRender);
$modx->smarty->assign('onSnipFormRender',$onSnipFormRender);

/* check unlock default element properties permission */
$unlock_element_properties = $modx->hasPermission('unlock_element_properties') ? 1 : 0;

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.local.property.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.element.properties.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.snippet.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/element/snippet/create.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-snippet-create"
        ,category: "'.($category != null ? $category->get('id') : '') .'"
    });
});
MODx.onSnipFormRender = "'.$onSnipFormRender.'";
MODx.perm.unlock_element_properties = "'.$unlock_element_properties.'";
// ]]>
</script>');

/* invoke OnSnipFormPrerender event */
$onSnipFormPrerender = $modx->invokeEvent('OnSnipFormPrerender',array(
    'id' => 0,
    'mode' => modSystemEvent::MODE_NEW,
));
if (is_array($onSnipFormPrerender)) $onSnipFormPrerender = implode('',$onSnipFormPrerender);
$modx->smarty->assign('onSnipFormPrerender',$onSnipFormPrerender);

return $modx->smarty->fetch('element/snippet/create.tpl');