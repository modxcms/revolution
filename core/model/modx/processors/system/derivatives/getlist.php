<?php
/**
 * Gets a list of derivative classes for a class
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.system.derivatives
 */
if (!$modx->hasPermission('class_map')) return $modx->error->failure($modx->lexicon('permission_denied'));

$class = $modx->getOption('class',$scriptProperties,'');
$skip = $modx->getOption('skip',$scriptProperties,'modXMLRPCResource');
$skip = explode(',',$skip);

$descendants = $modx->getDescendants($class);

$list = array();
foreach ($descendants as $descendant) {
    if (in_array($descendant,$skip)) continue;

    /** @var xPDOObject|modResource $obj */
    $obj = $modx->newObject($descendant);
    if (!$obj) continue;

    if ($class == 'modResource' && !$obj->showInContextMenu) continue;

    $list[] = array(
        'class' => $descendant,
    );
}

return $this->outputArray($list);