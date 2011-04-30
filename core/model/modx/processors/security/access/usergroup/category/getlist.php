<?php
/**
 * Gets a list of ACLs.
 *
 * @param string $type The type of ACL object
 * @param string $target (optional) The target of the ACL. Defauls to 0.
 * @param string $principal_class The class_key for the principal. Defaults to
 * modUserGroup.
 * @param string $principal (optional) The principal ID. Defaults to 0.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.access.usergroup.category
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('access','category');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'target');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

$usergroup = $modx->getOption('usergroup',$scriptProperties,0);
$category = $modx->getOption('category',$scriptProperties,false);
$policy = $modx->getOption('policy',$scriptProperties,false);

/* build query */
$c = $modx->newQuery('modAccessCategory');
$c->leftJoin('modCategory','Target');
$c->where(array(
    'principal_class' => 'modUserGroup',
    'principal' => $usergroup,
));
if (!empty($category)) $c->where(array('target' => $category));
if (!empty($policy)) $c->where(array('policy' => $policy));
$count = $modx->getCount('modAccessCategory',$c);
$c->leftJoin('modUserGroupRole','Role','Role.authority = modAccessCategory.authority');
$c->leftJoin('modAccessPolicy','Policy');
$c->select(array(
    'modAccessCategory.*',
    'Target.category AS name',
    'Role.name AS role_name',
    'Policy.name AS policy_name',
    'Policy.data AS policy_data',
));
$c->sortby('role_name','DESC');
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$acls = $modx->getCollection('modAccessCategory', $c);

/* iterate */
$list = array();
foreach ($acls as $acl) {
    $aclArray = $acl->toArray();
    if (empty($aclArray['name'])) $aclArray['name'] = '('.$modx->lexicon('none').')';
    $aclArray['authority_name'] = !empty($aclArray['role_name']) ? $aclArray['role_name'].' - '.$aclArray['authority'] : $aclArray['authority'];

    /* get permissions list */
    $data = $aclArray['policy_data'];
    unset($aclArray['policy_data']);
    $data = $modx->fromJSON($data);
    if (!empty($data)) {
        $permissions = array();
        foreach ($data as $perm => $v) {
            $permissions[] = $perm;
        }
        $aclArray['permissions'] = implode(', ',$permissions);
    }
    
    $aclArray['menu'] = array(
        array(
            'text' => $modx->lexicon('access_category_update'),
            'handler' => 'this.updateAcl',
        ),
        '-',
        array(
            'text' => $modx->lexicon('access_category_remove'),
            'handler' => 'this.confirm.createDelegate(this,["remove"])',
        ),
    );
    $list[] = $aclArray;
}
return $this->outputArray($list,$count);
