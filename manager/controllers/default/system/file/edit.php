<?php
/**
 * Loads the edit file page
 *
 * @package modx
 * @subpackage manager.system.file
 */
if (!$modx->hasPermission('file_view')) return $modx->error->failure($modx->lexicon('access_denied'));
if (empty($_GET['file'])) return $modx->error->failure($modx->lexicon('file_err_nf'));

$filename = preg_replace('#([\\\\]+|/{2,})#', '/',$_GET['file']);
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/system/file/edit.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">Ext.onReady(function() {MODx.load({xtype: "modx-page-file-edit",file: "'.$filename.'"});});</script>');

/* invoke OnFileEditFormPrerender event */
$onFileEditFormPrerender = $modx->invokeEvent('OnFileEditFormPrerender',array(
    'file' => $filename,
    'mode' => modSystemEvent::MODE_UPD,
));
if (is_array($onFileEditFormPrerender)) $onFileEditFormPrerender = implode('',$onFileEditFormPrerender);
$modx->smarty->assign('OnFileEditFormPrerender',$onFileEditFormPrerender);

return $modx->smarty->fetch('system/file/edit.tpl');