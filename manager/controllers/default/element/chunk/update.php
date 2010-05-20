<?php
/**
 * Load update chunk page
 *
 * @package modx
 * @subpackage manager.element.chunk
 */
if (!$modx->hasPermission('edit_chunk')) return $modx->error->failure($modx->lexicon('access_denied'));

/* grab chunk */
if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('chunk_err_ns'));
$chunk = $modx->getObject('modChunk',$_REQUEST['id']);
if (empty($chunk)) return $modx->error->failure($modx->lexicon('chunk_err_nfs',array('id' => $_REQUEST['id'])));
if (!$chunk->checkPolicy('view')) return $modx->error->failure($modx->lexicon('access_denied'));

if ($chunk->get('locked') && !$modx->hasPermission('edit_locked')) {
    return $modx->error->failure($modx->lexicon('chunk_err_locked'));
}

/* grab category for chunk, assign to parser */
$category = $chunk->getOne('Category');
$modx->smarty->assign('chunk',$chunk);

/* invoke OnChunkFormRender event */
$onChunkFormRender = $modx->invokeEvent('OnChunkFormRender',array(
    'id' => $_REQUEST['id'],
    'chunk' => &$chunk,
    'mode' => 'upd',
));
if (is_array($onChunkFormRender)) $onChunkFormRender = implode('', $onChunkFormRender);
$onChunkFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$onChunkFormRender);
$modx->smarty->assign('onChunkFormRender',$onChunkFormRender);

/* invoke OnRichTextEditorInit event */
if ($modx->getOption('use_editor')) {
    $onRTEInit = $modx->invokeEvent('OnRichTextEditorInit',array(
        'editor' => $which_editor,
        'elements' => array('post'),
        'chunk' => &$chunk,
    ));
    if (is_array($onRTEInit)) {
        $onRTEInit = implode('', $onRTEInit);
    }
    $modx->smarty->assign('onRTEInit',$onRTEInit);
}

/* check unlock default element properties permission */
$modx->smarty->assign('unlock_element_properties',$modx->hasPermission('unlock_element_properties') ? 1 : 0);

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.local.property.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.element.properties.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.chunk.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/element/chunk/update.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-chunk-update"
        ,id: "'.$chunk->get('id').'"
        ,name: "'.$chunk->get('name').'"
        ,category: "'.$chunk->get('category').'"
    });
});
MODx.onChunkFormRender = "'.$onChunkFormRender.'";
MODx.perm.unlock_element_properties = '.($modx->hasPermission('unlock_element_properties') ? 1 : 0).';
// ]]>
</script>');

/* invoke OnChunkFormPrerender event */
$onChunkFormPrerender = $modx->invokeEvent('OnChunkFormPrerender',array(
    'id' => $chunk->get('id'),
    'chunk' => &$chunk,
    'mode' => 'upd',
));
if (is_array($onChunkFormPrerender)) {
    $onChunkFormPrerender = implode('',$onChunkFormPrerender);
}
$modx->smarty->assign('onChunkFormPrerender',$onChunkFormPrerender);

/* display template */
return $modx->smarty->fetch('element/chunk/update.tpl');