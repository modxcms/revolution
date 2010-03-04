<?php
/**
 * Grabs the actions in node format
 *
 * @param string $id The parent node ID
 *
 * @package modx
 * @subpackage processors.system.action
 */
if (!$modx->hasPermission('actions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('action','menu');

$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$id = $modx->getOption('id',$scriptProperties,'n_0');

$ar = explode('_',$id);
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
