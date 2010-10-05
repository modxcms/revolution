<?php
/**
 * Gets a list of classes in the class map
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to class.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.classmap
 */
if (!$modx->hasPermission('class_map')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'class');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$parentClass = $modx->getOption('parentClass',$scriptProperties,'');

/* get content types */
$c = $modx->newQuery('modClassMap');
$count = $modx->getCount('modClassMap',$c);
if (!empty($parentClass)) {
    $c->where(array(
        'parent_class' => $parentClass,
    ));
}
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$classes = $modx->getCollection('modClassMap',$c);

$list = array();
foreach ($classes as $class) {
    $classArray = $class->toArray();
    $list[] = $classArray;
}
return $this->outputArray($list,$count);