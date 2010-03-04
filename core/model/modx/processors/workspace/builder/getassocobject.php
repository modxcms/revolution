<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder
 */
$modx->lexicon->load('workspace','package_builder');

if (!$modx->hasPermission('package_builder')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($scriptProperties['start'])) $scriptProperties['start'] = 0;
if (!isset($scriptProperties['limit'])) $scriptProperties['limit'] = 10;
if (!isset($scriptProperties['sort'])) $scriptProperties['sort'] = 'id';
if (!isset($scriptProperties['dir'])) $scriptProperties['dir'] = 'ASC';

if (!isset($scriptProperties['class_key'])) $scriptProperties['class_key'] = 'modResource';
$class_key = $scriptProperties['class_key'];

$c = $modx->newQuery($class_key);
$c->limit($scriptProperties['limit'],$scriptProperties['start']);
$objects = $modx->getCollection($class_key,$c);
$count = $modx->getCount($class_key);

$os = array();
foreach ($objects as $object) {
    $oa = $object->toArray();
    $pk = 'id';
    /* needs to be dynamic */
    switch ($class_key) {
        case 'modDocument':
        case 'modResource':
            $name = 'pagetitle';
            break;
        case 'modTemplate':
            $name = 'templatename';
            break;
        case 'modCategory':
            $name = 'category';
            break;
        case 'modContext':
            $name = 'key';
            $pk = 'key';
            break;
        case 'modAction':
            $name = 'controller';
            break;
        case 'modMenu':
            $name = 'text';
            break;
        default:
            $name = 'name';
            $pk = 'id';
            break;
    }
    $oa['name'] = $object->get($name);
    $id = $object->get($pk);
    if ($pk != 'id') { $oa['id'] = $id; }
    if ($id != null) { $oa['name'] .= ' ('.$id.')'; }

    $os[] = $oa;
}
return $this->outputArray($os,$count);