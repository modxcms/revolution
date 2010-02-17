<?php
/**
 * Grabs a list of elements by their subclass
 *
 * @package modx
 * @subpackage processors.element
 */
if (!$modx->hasPermission('view_element')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset','element');

/* get default properties */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$sort = $modx->getOption('sort',$_REQUEST,'name');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');

if (!isset($_REQUEST['element_class']) || $_REQUEST['element_class'] == '') {
    return $modx->error->failure($modx->lexicon('element_class_ns'));
}

$className = $_REQUEST['element_class'];
/* fix for template's different name field */
if ($className == 'modTemplate' && $_REQUEST['sort'] == 'name') $_REQUEST['sort'] = 'templatename';

$c = $modx->newQuery($className);
$count = $modx->getCount($className,$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$elements = $modx->getCollection($className,$c);

/* iterate */
$list = array();
foreach ($elements as $element) {
    $el = $element->toArray();

    $el['name'] = isset($el['templatename']) ? $el['templatename'] : $el['name'];

    $list[] = $el;
}

return $this->outputArray($list,$count);