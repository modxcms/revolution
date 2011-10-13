<?php
/**
 * Searches for specific resources and returns them in an array.
 *
 * @param integer $start The page to start on
 * @param integer $limit (optional) The number of results to limit by
 * @param string $sort The column to sort by
 * @param string $dir The direction to sort
 * @return array An array of modResources
 *
 * @package modx
 * @subpackage processors.resource
 */
if (!$modx->hasPermission('search')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'pagetitle');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

$contextKeys = array();
$contexts = $modx->getCollection('modContext', array('key:!=' => 'mgr'));
foreach ($contexts as $context) {
    if ($context->checkPolicy('list')) {
        $contextKeys[] = $context->get('key');
    }
}
if (empty($contextKeys)) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* setup query */
$c = $modx->newQuery('modResource');
$where = array('context_key:IN' => $contextKeys);
if (!empty($scriptProperties['id'])) $where['id'] = $scriptProperties['id'];
if (!empty($scriptProperties['parent'])) $where['parent'] = $scriptProperties['parent'];
if (!empty($scriptProperties['pagetitle'])) $where['pagetitle:LIKE'] = '%'.$scriptProperties['pagetitle'].'%';
if (!empty($scriptProperties['longtitle'])) $where['longtitle:LIKE'] = '%'.$scriptProperties['longtitle'].'%';
if (!empty($scriptProperties['introtext'])) $where['introtext:LIKE'] = '%'.$scriptProperties['introtext'].'%';
if (!empty($scriptProperties['description'])) $where['description:LIKE'] = '%'.$scriptProperties['description'].'%';
if (!empty($scriptProperties['alias'])) $where['alias:LIKE'] = '%'.$scriptProperties['alias'].'%';
if (!empty($scriptProperties['menutitle'])) $where['menutitle:LIKE'] = '%'.$scriptProperties['menutitle'].'%';
if (!empty($scriptProperties['content'])) $where['content:LIKE'] = '%'.$scriptProperties['content'].'%';

if (!empty($scriptProperties['published'])) $where['published'] = true;
if (!empty($scriptProperties['unpublished'])) $where['published'] = false;
if (!empty($scriptProperties['deleted'])) $where['deleted'] = true;
if (!empty($scriptProperties['undeleted'])) $where['deleted'] = false;

$c->where($where);
$count = $modx->getCount('modResource',$c);
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$iterator = $modx->getIterator('modResource',$c);
$actions = $modx->request->getAllActionIDs();

/* iterate */
$list = array();
$charset = $modx->getOption('modx_charset',null,'UTF-8');
/** @var modResource $resource */
foreach ($iterator as $resource) {
    if ($resource->checkPolicy('list')) {
        $resourceArray = $resource->toArray();
        $resourceArray['menu'] = array();
        $resourceArray['pagetitle'] = htmlentities($resourceArray['pagetitle'],ENT_COMPAT,$charset);
        $resourceArray['description'] = htmlentities($resourceArray['description'],ENT_COMPAT,$charset);
        if ($modx->hasPermission('edit_document')) {
            $resourceArray['menu'][] = array(
                'text' => $modx->lexicon('resource_edit'),
                'params' => array('a' => $actions['resource/update']),
            );
        }
        $list[] = $resourceArray;
    }
}

return $this->outputArray($list,$count);