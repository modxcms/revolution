<?php
/**
 * Load update snippet page
 *
 * @package modx
 * @subpackage manager.element.snippet
 */
if(!$modx->hasPermission('edit_snippet')) return $modx->error->failure($modx->lexicon('access_denied'));

/* get snippet */
$snippet = $modx->getObject('modSnippet',$_REQUEST['id']);
if ($snippet == null) return $modx->error->failure($modx->lexicon('snippet_err_nf'));
if (!$snippet->checkPolicy('view')) return $modx->error->failure($modx->lexicon('access_denied'));

if ($snippet->get('locked') && !$modx->hasPermission('edit_locked')) {
    return $modx->error->failure($modx->lexicon('snippet_err_locked'));
}

/* invoke onSnipFormRender event */
$onSnipFormRender = $modx->invokeEvent('OnSnipFormRender',array(
    'id' => $snippet->get('id'),
    'snippet' => &$snippet,
    'mode' => modSystemEvent::MODE_UPD,
));
if (is_array($onSnipFormRender)) $onSnipFormRender = implode('',$onSnipFormRender);
$onSnipFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$onSnipFormRender);
$modx->smarty->assign('onSnipFormRender',$onSnipFormRender);

/* get properties */
$properties = $snippet->get('properties');
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

$snippetArray = $snippet->toArray();
$snippetArray['properties'] = $data;
if (strpos($snippetArray['snippet'],'<?php') === false) {
    $snippetArray['snippet'] = "<?php\n".$snippetArray['snippet'];
}
/* check unlock default element properties permission */
$unlock_element_properties = $modx->hasPermission('unlock_element_properties') ? 1 : 0;

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.local.property.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.element.properties.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.snippet.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/element/snippet/update.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-snippet-update"
        ,snippet: "'.$snippet->get('id').'"
        ,record: '.$modx->toJSON($snippetArray).'
    });
});
MODx.onSnipFormRender = "'.$onSnipFormRender.'";
MODx.perm.unlock_element_properties = "'.$unlock_element_properties.'";
// ]]>
</script>');

/* invoke OnSnipFormPrerender event */
$onSnipFormPrerender = $modx->invokeEvent('OnSnipFormPrerender',array(
    'id' => $snippet->get('id'),
    'snippet' => &$snippet,
    'mode' => modSystemEvent::MODE_UPD,
));
if (is_array($onSnipFormPrerender)) $onSnipFormPrerender = implode('',$onSnipFormPrerender);
$modx->smarty->assign('onSnipFormPrerender',$onSnipFormPrerender);

/* assign snippet to parser and display template */
$modx->smarty->assign('snippet',$snippet);

$modx->smarty->assign('_pagetitle',$modx->lexicon('snippet').': '.$snippet->get('name'));
$this->checkFormCustomizationRules($snippet);
return $modx->smarty->fetch('element/snippet/update.tpl');