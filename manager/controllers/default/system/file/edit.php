<?php
/**
 * Loads the edit file page
 *
 * @package modx
 * @subpackage manager.system.file
 */
if (!$modx->hasPermission('file_view')) return $modx->error->failure($modx->lexicon('access_denied'));
if (empty($_GET['file'])) return $modx->error->failure($modx->lexicon('file_err_nf'));

/* format filename */
$filename = preg_replace('#([\\\\]+|/{2,})#', '/',$_GET['file']);
$modx->getService('fileHandler','modFileHandler');
$file = $modx->fileHandler->make($filename);

if (!$file->exists()) return $modx->error->failure($modx->lexicon('file_err_nf'));
if (!$file->isReadable()) {
    return $modx->error->failure($modx->lexicon('file_err_perms'));
}
$imagesExts = array('jpg','jpeg','png','gif','ico');
$fileExtension = pathinfo($filename,PATHINFO_EXTENSION);

$fa = array(
    'name' => $file->getPath(),
    'size' => $file->getSize(),
    'last_accessed' => $file->getLastAccessed(),
    'last_modified' => $file->getLastModified(),
    'content' => $file->getContents(),
    'image' => in_array($fileExtension,$imagesExts) ? true : false,
);

/* register JS */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/system/file/edit.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-file-edit"
        ,file: "'.$filename.'"
        ,record: '.$modx->toJSON($fa).'
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

$this->checkFormCustomizationRules();
return $modx->smarty->fetch('system/file/edit.tpl');