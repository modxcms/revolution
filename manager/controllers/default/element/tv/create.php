<?php
/**
 * Load create tv page
 *
 * @package modx
 * @subpackage manager.element.tv
 */
if (!$modx->hasPermission('new_tv')) return $modx->error->failure($modx->lexicon('access_denied'));

/* preset category if specified */
if (isset($_REQUEST['category'])) {
    $category = $modx->getObject('modCategory',$_REQUEST['category']);
    if ($category != null) $modx->smarty->assign('category',$category);
} else { $category = null; }

/* invoke OnTVFormRender event */
$onTVFormRender = $modx->invokeEvent('OnTVFormRender',array(
    'id' => 0,
    'mode' => modSystemEvent::MODE_NEW,
));
if (is_array($onTVFormRender)) $onTVFormRender = implode('',$onTVFormRender);
$onTVFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$onTVFormRender);
$modx->smarty->assign('onTVFormRender',$onTVFormRender);

/* check unlock default element properties permission */
$unlock_element_properties = $modx->hasPermission('unlock_element_properties') ? 1 : 0;

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.local.property.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.element.properties.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.tv.template.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.tv.security.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.tv.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/element/tv/create.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-tv-create"
        ,category: "'.($category != null ? $category->get('id') : ''). '"
    });
});
var onTVFormRender = "'.$onTVFormRender.'";
MODx.perm.unlock_element_properties = "'.$unlock_element_properties.'";
// ]]>
</script>');

/* invoke OnTVFormPrerender event */
$onTVFormPrerender = $modx->invokeEvent('OnTVFormPrerender',array(
    'id' => 0,
    'mode' => modSystemEvent::MODE_NEW,
));
if(is_array($onTVFormPrerender)) $onTVFormPrerender = implode('',$onTVFormPrerender);
$modx->smarty->assign('onTVFormPrerender',$onTVFormPrerender);

/* display template */
return $modx->smarty->fetch('element/tv/create.tpl');