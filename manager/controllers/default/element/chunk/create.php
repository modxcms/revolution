<?php
/**
 * Load create chunk page
 *
 * @package modx
 * @subpackage manager.element.chunk
 */
if (!$modx->hasPermission('new_chunk')) return $modx->error->failure($modx->lexicon('access_denied'));

/* grab default category if specified */
if (isset($_REQUEST['category'])) {
    $category = $modx->getObject('modCategory',$_REQUEST['category']);
    if ($category && $category instanceof modCategory) {
        $modx->smarty->assign('category',$category);
    }
} else { $category = null; }

/* if RTE is being reset, switch */
$which_editor = isset($_POST['which_editor']) ? $_POST['which_editor'] : 'none';
$modx->smarty->assign('which_editor',$which_editor);

/* invoke OnChunkFormRender event */
$onChunkFormRender = $modx->invokeEvent('OnChunkFormRender',array(
    'id' => 0,
    'mode' => 'new',
    'chunk' => null,
));
if (is_array($onChunkFormRender)) $onChunkFormRender = implode('', $onChunkFormRender);
$onChunkFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$onChunkFormRender);
$modx->smarty->assign('onChunkFormRender',$onChunkFormRender);


/* invoke OnRichTextEditorInit event */
if ($modx->getOption('use_editor') == 1) {
    $onRTEInit = $modx->invokeEvent('OnRichTextEditorInit',array(
        'editor' => $which_editor,
        'elements' => array('post'),
    ));
    if (is_array($onRTEInit)) {
        $onRTEInit = implode('', $onRTEInit);
    }
    $modx->smarty->assign('onRTEInit',$onRTEInit);
}

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.local.property.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.element.properties.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.chunk.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/element/chunk/create.js');

$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-chunk-create"
        ,category: "'.(isset($category) && $category instanceof modCategory ? $category->get('id') : '').'"
    });
});
MODx.onChunkFormRender = "'.$onChunkFormRender.'";
MODx.perm.unlock_element_properties = '.($modx->hasPermission('unlock_element_properties') ? 1 : 0).';
// ]]>
</script>');

/* invoke OnChunkFormPrerender event */
$onChunkFormPrerender = $modx->invokeEvent('OnChunkFormPrerender',array(
    'id' => 0,
    'mode' => 'new',
    'chunk' => null,
));
if (is_array($onChunkFormPrerender)) {
    $onChunkFormPrerender = implode('',$onChunkFormPrerender);
}
$modx->smarty->assign('onChunkFormPrerender',$onChunkFormPrerender);

/* display template */
return $modx->smarty->fetch('element/chunk/create.tpl');