<?php
/**
 * Outputs a list of Element subclasses
 *
 * @package modx
 * @subpackage processors.element
 */
if (!$modx->hasPermission('view_element')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* setup defaults */
$isLimit = !empty($scriptProperties['limit']);
$sort = $modx->getOption('sort',$scriptProperties,'class');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$limit = $modx->getOption('limit',$scriptProperties,10);
$start = $modx->getOption('start',$scriptProperties,0);

/* build query */
$c = $modx->newQuery('modClassMap');
$c->where(array(
    'parent_class' => 'modElement',
    'class:!=' => 'modTemplate',
));
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$classes = $modx->getCollection('modClassMap',$c);

/* iterate */
$list = array();
foreach ($classes as $class) {
    $el = array( 'name' => $class->get('class') );

    $list[] = $el;
}

return $this->outputArray($list);