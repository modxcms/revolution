<?php
/**
 * Load create plugin page
 *
 * @package modx
 * @subpackage manager.element.plugin
 */
if (!$modx->hasPermission('new_plugin')) return $modx->error->failure($modx->lexicon('access_denied'));

/* grab category if preset */
if (isset($_REQUEST['category'])) {
	$category = $modx->getObject('modCategory',$_REQUEST['category']);
	if ($category != null) $modx->smarty->assign('category',$category);
} else { $category = null; }

/* invoke OnPluginFormPrerender event */
$onPluginFormPrerender = $modx->invokeEvent('OnPluginFormPrerender',array('id' => $_REQUEST['id']));
if (is_array($onPluginFormPrerender)) $onPluginFormPrerender = implode('',$onPluginFormPrerender);
$modx->smarty->assign('onPluginFormPrerender',$onPluginFormPrerender);

/* invoke OnPluginFormRender event */
$onPluginFormRender = $modx->invokeEvent('OnPluginFormRender',array('id' => $_REQUEST['id']));
if (is_array($onPluginFormRender)) $onPluginFormRender = implode('',$onPluginFormRender);
$modx->smarty->assign('onPluginFormRender',$onPluginFormRender);

/* check unlock default element properties permission */
$unlock_element_properties = $modx->hasPermission('unlock_element_properties') ? 1 : 0;

/* register JS scripts */
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/widgets/core/modx.grid.local.property.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/widgets/element/modx.grid.element.properties.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/widgets/element/modx.grid.plugin.event.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/widgets/element/modx.panel.element.properties.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/widgets/element/modx.panel.plugin.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/sections/element/plugin/common.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/sections/element/plugin/create.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-plugin-create"
        ,category: "'.($category != null ? $category->get('id') : '').'"
    });
});
var onPluginFormRender = "'.$onPluginFormRender.'";
MODx.perm.unlock_element_properties = "'.$unlock_element_properties.'";
// ]]>
</script>');

return $modx->smarty->fetch('element/plugin/create.tpl');