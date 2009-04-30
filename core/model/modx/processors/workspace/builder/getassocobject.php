<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder
 */
$modx->lexicon->load('workspace','package_builder');

if (!$modx->hasPermission('package_builder')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'id';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

if (!isset($_REQUEST['class_key'])) $_REQUEST['class_key'] = 'modResource';
$class_key = $_REQUEST['class_key'];

$c = $modx->newQuery($class_key);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
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