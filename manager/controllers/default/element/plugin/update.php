<?php
/**
 * Load update plugin page
 *
 * @package modx
 * @subpackage manager.element.plugin
 */
if (!$modx->hasPermission('edit_plugin')) return $modx->error->failure($modx->lexicon('access_denied'));

/* load plugin */
if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('plugin_err_ns'));
$plugin = $modx->getObject('modPlugin',$_REQUEST['id']);
if ($plugin == null) return $modx->error->failure($modx->lexicon('plugin_err_nf'));
if (!$plugin->checkPolicy('view')) return $modx->error->failure($modx->lexicon('access_denied'));

$plugin->category = $plugin->getOne('Category');

/* invoke OnPluginFormRender event */
$onPluginFormRender = $modx->invokeEvent('OnPluginFormRender',array(
    'id' => $plugin->get('id'),
    'plugin' => &$plugin,
    'mode' => modSystemEvent::MODE_UPD,
));
if (is_array($onPluginFormRender)) $onPluginFormRender = implode('',$onPluginFormRender);
$onPluginFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$onPluginFormRender);
$modx->smarty->assign('onPluginFormRender',$onPluginFormRender);

/* check unlock default element properties permission */
$modx->smarty->assign('unlock_element_properties',$modx->hasPermission('unlock_element_properties') ? 1 : 0);

/* load plugin into parser */
$modx->smarty->assign('plugin',$plugin);

/* check unlock default element properties permission */
$unlock_element_properties = $modx->hasPermission('unlock_element_properties') ? 1 : 0;

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.local.property.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.element.properties.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.plugin.event.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.plugin.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/element/plugin/common.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/element/plugin/update.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-plugin-update"
        ,id: "'.$plugin->get('id').'"
        ,category: "'.$plugin->get('category').'"
    });
});
MODx.onPluginFormRender = "'.$onPluginFormRender.'";
MODx.perm.unlock_element_properties = "'.$unlock_element_properties.'";
// ]]>
</script>');

/* invoke OnPluginFormPrerender event */
$onPluginFormPrerender = $modx->invokeEvent('OnPluginFormPrerender',array(
    'id' => $plugin->get('id'),
    'plugin' => &$plugin,
    'mode' => modSystemEvent::MODE_UPD,
));
if (is_array($onPluginFormPrerender)) $onPluginFormPrerender = implode('',$onPluginFormPrerender);
$modx->smarty->assign('onPluginFormPrerender',$onPluginFormPrerender);
return $modx->smarty->fetch('element/plugin/update.tpl');
