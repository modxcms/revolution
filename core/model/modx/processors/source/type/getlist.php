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
$coreSources = $modx->getOption('core_media_sources',null,'modFileMediaSource,modS3MediaSource');
$coreSources = explode(',',$coreSources);

$list = array();
foreach ($descendants as $descendant) {
    $key = in_array($descendant,$coreSources) ? 'sources.'.$descendant : $descendant;
    /** @var xPDOObject|modMediaSource $obj */
    $obj = $modx->newObject($key);
    if (!$obj) continue;

    $list[] = array(
        'class' => $key,
        'name' => $obj->getTypeName(),
        'description' => $obj->getTypeDescription(),
    );
}

return $this->outputArray($list);