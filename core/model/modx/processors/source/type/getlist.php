<?php
/**
 * Gets a list of media source types
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.source.type
 */
if (!$modx->hasPermission('sources')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('sources');

$modx->setPackageMeta('sources',$modx->getOption('core_path',null,MODX_CORE_PATH).'model/modx/');
$modx->loadClass('sources.modMediaSource');
$descendants = $modx->getDescendants('modMediaSource');

$list = array();
foreach ($descendants as $descendant) {
    /** @var xPDOObject|modMediaSource $obj */
    $obj = $modx->newObject('sources.'.$descendant);
    if (!$obj) continue;

    $list[] = array(
        'class' => $descendant,
        'description' => $obj->getTypeDescription(),
    );
}

return $this->outputArray($list);