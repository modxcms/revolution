<?php
/**
 * Load create template page
 *
 * @package modx
 * @subpackage manager.element.template
 */
if (!$modx->hasPermission('new_template')) return $modx->error->failure($modx->lexicon('access_denied'));

/* preset category if specified */
if (isset($_REQUEST['category'])) {
	$category = $modx->getObject('modCategory',$_REQUEST['category']);
	if ($category != null) $modx->smarty->assign('category',$category);
} else { $category = null; }

/* invoke OnTempFormRender event */
$onTempFormRender = $modx->invokeEvent('OnTempFormRender',array(
    'id' => 0,
    'mode' => modSystemEvent::MODE_NEW,
));
if (is_array($onTempFormRender)) $onTempFormRender = implode('',$onTempFormRender);
$onTempFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$onTempFormRender);
$modx->smarty->assign('onTempFormRender',$onTempFormRender);

/* check unlock default element properties permission */
$unlock_element_properties = $modx->hasPermission('unlock_element_properties') ? 1 : 0;

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.local.property.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.element.properties.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.template.tv.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.template.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/element/template/create.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-template-create"
        ,category: "'.($category != null ? $category->get('id') : ''). '"
    });
});
MODx.onTempFormRender = "'.$onTempFormRender.'";
MODx.perm.unlock_element_properties = "'.$unlock_element_properties.'";
// ]]>
</script>');

/* invoke OnTempFormPrerender event */
$onTempFormPrerender = $modx->invokeEvent('OnTempFormPrerender',array(
    'id' => 0,
    'mode' => modSystemEvent::MODE_NEW,
));
if (is_array($onTempFormPrerender)) $onTempFormPrerender = implode('',$onTempFormPrerender);
$modx->smarty->assign('onTempFormPrerender',$onTempFormPrerender);

/* display template */
return $modx->smarty->fetch('element/template/create.tpl');