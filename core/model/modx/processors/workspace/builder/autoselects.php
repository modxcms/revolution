<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder
 */
$modx->lexicon->load('workspace','package_builder');

if (!$modx->hasPermission('package_builder')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['classes'])) {
	$_POST['classes'] = array();
}
$_SESSION['modx.pb']['autoselects'] = $_POST['classes'];

return $modx->error->success();