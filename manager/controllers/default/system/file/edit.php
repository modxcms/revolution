<?php
/**
 * Loads the edit file page
 *
 * @package modx
 * @subpackage manager.system.file
 */
if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/system/file/edit.js');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">Ext.onReady(function() {MODx.load({xtype: "modx-page-file-edit",file: "'.$_GET['file'].'"});});</script>');

return $modx->smarty->fetch('system/file/edit.tpl');