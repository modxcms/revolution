<?php
/**
 * Loads the edit file page
 *
 * @package modx
 * @subpackage manager.system.file
 */
if (!$modx->hasPermission('file_view')) return $modx->error->failure($modx->lexicon('access_denied'));
if (empty($_GET['file'])) return $modx->error->failure($modx->lexicon('file_err_nf'));
$wctx = !empty($_GET['wctx']) ? $_GET['wctx'] : $modx->context->get('key');

if (!empty($wctx)) {
    $workingContext = $modx->getContext($wctx);
    if (!$workingContext) {
        return $modx->error->failure($modx->error->failure($modx->lexicon('permission_denied')));
    }
} else {
    $workingContext =& $modx->context;
}

/* format filename */
$filename = preg_replace('#([\\\\]+|/{2,})#', '/',$_GET['file']);
$modx->getService('fileHandler', 'modFileHandler', '',array('context' => $wctx));
$root = $modx->fileHandler->getBasePath(false);
if ($workingContext->getOption('filemanager_path_relative',true)) {
    $root = $workingContext->getOption('base_path','').$root;
}
$file = $modx->fileHandler->make($root.$filename);

if (!$file->exists()) return $modx->error->failure($modx->lexicon('file_err_nf'));
if (!$file->isReadable()) {
    return $modx->error->failure($modx->lexicon('file_err_perms'));
}
$imagesExts = array('jpg','jpeg','png','gif','ico');
$fileExtension = pathinfo($filename,PATHINFO_EXTENSION);

$fa = array(
    'name' => $filename,//$file->getPath(),
    'size' => $file->getSize(),
    'last_accessed' => $file->getLastAccessed(),
    'last_modified' => $file->getLastModified(),
    'content' => $file->getContents(),
    'image' => in_array($fileExtension,$imagesExts) ? true : false,
);
$canSave = $file->isWritable() ? true : false;

/* register JS */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/system/file/edit.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-file-edit"
        ,file: "'.$filename.'"
        ,record: '.$modx->toJSON($fa).'
        ,canSave: '.($canSave ? 1 : 0).'
    });
});</script>');

/* invoke OnFileEditFormPrerender event */
$onFileEditFormPrerender = $modx->invokeEvent('OnFileEditFormPrerender',array(
    'file' => $filename,
    'mode' => modSystemEvent::MODE_UPD,
    'fa' => &$fa,
));
if (is_array($onFileEditFormPrerender)) $onFileEditFormPrerender = implode('',$onFileEditFormPrerender);
$modx->smarty->assign('OnFileEditFormPrerender',$onFileEditFormPrerender);


$modx->smarty->assign('fa',$fa);

$modx->smarty->assign('_pagetitle',$modx->lexicon('file_edit').': '.basename($filename));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('system/file/edit.tpl');
