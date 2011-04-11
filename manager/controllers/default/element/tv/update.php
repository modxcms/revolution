<?php
/**
 * Load create template page
 *
 * @package modx
 * @subpackage manager.element.tv
 */
if (!$modx->hasPermission('edit_tv')) return $modx->error->failure($modx->lexicon('access_denied'));

/* get tv */
$tv = $modx->getObject('modTemplateVar',$_REQUEST['id']);
if ($tv == null) return $modx->error->failure($modx->lexicon('tv_err_not_found'));
if (!$tv->checkPolicy('view')) return $modx->error->failure($modx->lexicon('access_denied'));

if ($tv->get('locked') && !$modx->hasPermission('edit_locked')) {
    return $modx->error->failure($modx->lexicon('tv_err_locked'));
}

/* invoke OnTVFormRender event */
$onTVFormRender = $modx->invokeEvent('OnTVFormRender',array(
    'id' => $tv->get('id'),
    'tv' => &$tv,
    'mode' => modSystemEvent::MODE_UPD,
));
if (is_array($onTVFormRender)) $onTVFormRender = implode('',$onTVFormRender);
$onTVFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$onTVFormRender);
$modx->smarty->assign('onTVFormRender',$onTVFormRender);

/* get properties */
$properties = $tv->get('properties');
if (!is_array($properties)) $properties = array();

$data = array();
foreach ($properties as $property) {
    $data[] = array(
        $property['name'],
        $property['desc'],
        $property['type'],
        $property['options'],
        $property['value'],
        $property['lexicon'],
        false, /* overridden set to false */
        $property['desc_trans'],
    );
}
$tvArray = $tv->toArray();
$tvArray['properties'] = $data;

/* check unlock default element properties permission */
$unlock_element_properties = $modx->hasPermission('unlock_element_properties') ? 1 : 0;

/* assign TV to parser */
$modx->smarty->assign('tv',$tv);

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.local.property.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.element.properties.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.tv.template.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.tv.security.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.tv.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/element/tv/update.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-tv-update"
        ,id: "'.$tv->get('id').'"
        ,record: '.$modx->toJSON($tvArray).'
    });
});
var onTVFormRender = "'.$onTVFormRender.'";
MODx.perm.unlock_element_properties = "'.$unlock_element_properties.'";
// ]]>
</script>');

/* invoke OnTVFormPrerender event */
$onTVFormPrerender = $modx->invokeEvent('OnTVFormPrerender',array(
    'id' => $tv->get('id'),
    'tv' => &$tv,
    'mode' => modSystemEvent::MODE_UPD,
));
if(is_array($onTVFormPrerender)) $onTVFormPrerender = implode('',$onTVFormPrerender);
$modx->smarty->assign('onTVFormPrerender',$onTVFormPrerender);

$modx->smarty->assign('_pagetitle',$modx->lexicon('tv').': '.$tv->get('name'));
$this->checkFormCustomizationRules($tv);
return $modx->smarty->fetch('element/tv/update.tpl');