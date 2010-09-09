<?php
/**
 * Get the resource groups as nodes
 *
 * @param string $id The ID of the parent node
 *
 * @package modx
 * @subpackage processors.security.documentgroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('access');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* get parent */
$scriptProperties['id'] = !isset($scriptProperties['id']) ? 0 : str_replace('n_dg_','',$scriptProperties['id']);
$resourceGroup = $modx->getObject('modResourceGroup',$scriptProperties['id']);

$c = $modx->newQuery('modResourceGroup');
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$groups = $modx->getCollection('modResourceGroup',$c);

$list = array();
if ($resourceGroup == null) {
    foreach ($groups as $group) {
        $list[] = array(
            'text' => $group->get('name'),
            'id' => 'n_dg_'.$group->get('id'),
            'leaf' => 0,
            'type' => 'modResourceGroup',
            'cls' => 'icon-resourcegroup',
        );
    }
} else {
    $resources = $resourceGroup->getResources();
    foreach ($resources as $resource) {
        $list[] = array(
            'text' => $resource->get('pagetitle'),
            'id' => 'n_'.$resource->get('id'),
            'leaf' => 1,
            'type' => 'modResource',
            'cls' => 'icon-'.$resource->get('class_key'),
        );
    }
}

return $this->toJSON($list);