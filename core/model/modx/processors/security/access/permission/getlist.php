<?php
/**
 * @package modx
 * @subpackage processors.security.permission
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('access','permissions');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$query = $modx->getOption('query',$scriptProperties,'');

/* build query */
$c = $modx->newQuery('modAccessPermission');
$c->select(array(
    'modAccessPermission.id',
    'modAccessPermission.name',
    'modAccessPermission.description',
    'Template.lexicon',
));
$c->leftJoin('modAccessPolicyTemplate','Template');
$c->query['DISTINCT'] = 'DISTINCT';
if (!empty($query)) {
    $c->where(array(
        'modAccessPermission.name:LIKE' => '%'.$query.'%',
    ));
}
$count = $modx->getCount('modAccessPermission',$c);
$c->groupby('modAccessPermission.name');
$c->sortby('modAccessPermission.name','ASC');
if ($isLimit) {
    $c->limit($limit,$start);
}
$permissions = $modx->getCollection('modAccessPermission',$c);

/* iterate */
$list = array();
foreach ($permissions as $permission) {
    $permissionArray = $permission->get(array('name','description'));

    $lexicon = $permission->get('lexicon');
    if (!empty($lexicon)) {
        if (strpos($lexicon,':') !== false) {
            $modx->lexicon->load($lexicon);
        } else {
            $modx->lexicon->load('core:'.$lexicon);
        }
        $desc = $modx->lexicon($desc);
    }
    $permissionArray['description'] = $modx->lexicon($permissionArray['description']);
    $list[] = $permissionArray;
}

return $this->outputArray($list,$count);