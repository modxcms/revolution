<?php
/**
 * Load update template page
 *
 * @package modx
 * @subpackage manager.element.template
 */
if(!$modx->hasPermission('edit_template')) return $modx->error->failure($modx->lexicon('access_denied'));

/* load template */
$template = $modx->getObject('modTemplate',$_REQUEST['id']);
if ($template == null) return $modx->error->failure($modx->lexicon('template_err_not_found'));
if ($template->get('locked') && !$modx->hasPermission('edit_locked')) {
    return $modx->error->failure($modx->lexicon('template_err_locked'));
}

$template->category = $template->getOne('Category');

/* invoke OnTempFormPrerender event */
$onTempFormPrerender = $modx->invokeEvent('OnTempFormPrerender',array('id' => $_REQUEST['id']));
if (is_array($onTempFormPrerender)) $onTempFormPrerender = implode('',$onTempFormPrerender);
$modx->smarty->assign('onTempFormPrerender',$onTempFormPrerender);

/* invoke OnTempFormRender event */
$onTempFormRender = $modx->invokeEvent('OnTempFormRender',array('id' => $_REQUEST['id']));
if (is_array($onTempFormRender)) $onTempFormRender = implode('',$onTempFormRender);
$modx->smarty->assign('onTempFormRender',$onTempFormRender);

/* check unlock default element properties permission */
$unlock_element_properties = $modx->hasPermission('unlock_element_properties') ? 1 : 0;

/* assign template to parser */
$modx->smarty->assign('template',$template);

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.local.property.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.element.properties.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.template.tv.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.element.properties.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.template.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/element/template/update.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-template-update"
        ,id: "'.$template->get('id').'"
        ,category: "'.$template->get('category'). '"
    });
});
MODx.onTempFormRender = "'.$onTempFormRender.'";
MODx.perm.unlock_element_properties = "'.$unlock_element_properties.'";
// ]]>
</script>');

return $modx->smarty->fetch('element/template/update.tpl');