<?php
/**
 * Grabs a list of elements by their subclass
 *
 * @package modx
 * @subpackage processors.element
 */
if (!$modx->hasPermission('class_map')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('propertyset','element');

/* get default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

if (!isset($scriptProperties['element_class']) || $scriptProperties['element_class'] == '') {
    return $modx->error->failure($modx->lexicon('element_class_ns'));
}

$className = $scriptProperties['element_class'];
/* fix for template's different name field */
if ($className == 'modTemplate' && $scriptProperties['sort'] == 'name') $scriptProperties['sort'] = 'templatename';

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