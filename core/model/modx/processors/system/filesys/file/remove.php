<?php
/**
 * @package modx
 * @subpackage processors.system.filesys.file
 */

if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));

$file = $_POST['path'].$_POST['file'];

if (!file_exists($file)) return $modx->error->failure($modx->lexicon('file_err_nf'));

if (!@unlink($file)) return $modx->error->failure($modx->lexicon('file_err_remove'));

return $modx->error->success();