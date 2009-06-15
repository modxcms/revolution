<?php
/**
 * Grabs the actions in node format
 *
 * @param string $id The parent node ID
 *
 * @package modx
 * @subpackage processors.system.action
 */
$modx->lexicon->load('action','menu');

if (!$modx->hasPermission('actions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['id'])) $_REQUEST['id'] = 'n_0';

$ar = explode('_',$_REQUEST['id']);
$type = $ar[1];
$pk = $ar[2];

$list = array();
/* root - get namespaces */
if ($type == 'root') {
    $list = include dirname(__FILE__).'/getnodes.root.php';

/* namespace actions */
} else if ($type == 'namespace') {
    $list = include dirname(__FILE__).'/getnodes.namespace.php';

/* subactions */
} else {
    $list = include dirname(__FILE__).'/getnodes.subaction.php';
}

return $this->toJSON($list);
