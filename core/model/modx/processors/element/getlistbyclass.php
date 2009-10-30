<?php
/**
 * Grabs a list of elements by their subclass
 *
 * @package modx
 * @subpackage processors.element
 */
if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset','element');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';


if (!isset($_REQUEST['element_class']) || $_REQUEST['element_class'] == '') {
    return $modx->error->failure($modx->lexicon('element_class_ns'));
}

$className = $_REQUEST['element_class'];
/* fix for template's different name field */
if ($className == 'modTemplate' && $_REQUEST['sort'] == 'name') $_REQUEST['sort'] = 'templatename';

$c = $modx->newQuery($className);
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if (isset($_REQUEST['limit'])) {
    $c = $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$elements = $modx->getCollection($className,$c);
$count = $modx->getCount($className);


$list = array();
foreach ($elements as $element) {
    $el = $element->toArray();

    $el['name'] = isset($el['templatename']) ? $el['templatename'] : $el['name'];

    $list[] = $el;
}

return $this->outputArray($list,$count);