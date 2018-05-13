<?php

namespace MODX\Processors\Security\Access\UserGroup\Namespaces;

use MODX\modAccessResourceGroup;
use MODX\modUserGroup;
use MODX\Processors\modObjectGetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of ACLs.
 *
 * @param string $type The type of ACL object
 * @param string $target (optional) The target of the ACL. Defaults to 0.
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
 * @subpackage processors.security.access.usergroup.namespace
 */
class GetList extends modObjectGetListProcessor
{
    public $classKey = 'modAccessNamespace';
    public $languageTopics = ['access'];
    public $permission = 'access_permissions';
    public $defaultSortField = 'target';
    public $defaultSortDirection = 'ASC';
    /** @var modUserGroup $userGroup */
    public $userGroup;


    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'usergroup' => 0,
            'namespace' => false,
            'policy' => false,
        ]);
        $usergroup = $this->getProperty('usergroup', false);
        if (!empty($usergroup)) {
            $this->userGroup = $this->modx->getObject('modUserGroup', $usergroup);
        }

        return $initialized;
    }


    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $userGroup = $this->getProperty('usergroup');
        $c->where([
            'principal_class' => 'modUserGroup',
            'principal' => $userGroup,
        ]);
        $namespace = $this->getProperty('namespace', false);
        if (!empty($namespace)) {
            $c->where(['target' => $namespace]);
        }
        $policy = $this->getProperty('policy', false);
        if (!empty($policy)) {
            $c->where(['policy' => $policy]);
        }

        return $c;
    }


    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->leftJoin('modNamespace', 'Target');
        $c->leftJoin('modUserGroupRole', 'Role', [
            'Role.authority = modAccessNamespace.authority',
        ]);
        $c->leftJoin('modAccessPolicy', 'Policy');
        $c->select($this->modx->getSelectColumns('modAccessNamespace', 'modAccessNamespace'));
        $c->select([
            'name' => 'Target.name',
            'role_name' => 'Role.name',
            'policy_name' => 'Policy.name',
            'policy_data' => 'Policy.data',
        ]);

        return $c;
    }


    /**
     * @param xPDOObject|modAccessResourceGroup $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        if (empty($objectArray['name'])) $objectArray['name'] = '(' . $this->modx->lexicon('none') . ')';
        $objectArray['authority_name'] = !empty($objectArray['role_name'])
            ? $objectArray['role_name'] . ' - ' . $objectArray['authority'] : $objectArray['authority'];

        /* get permissions list */
        $data = $objectArray['policy_data'];
        unset($objectArray['policy_data']);
        $data = json_decode($data, true);
        if (!empty($data)) {
            $permissions = [];
            foreach ($data as $perm => $v) {
                $permissions[] = $perm;
            }
            $objectArray['permissions'] = implode(', ', $permissions);
        }


        $cls = 'pedit premove';

        $objectArray['cls'] = $cls;
        $objectArray['menu'] = [
            [
                'text' => $this->modx->lexicon('access_namespace_update'),
                'handler' => 'this.updateAcl',
            ],
            '-',
            [
                'text' => $this->modx->lexicon('access_namespace_remove'),
                'handler' => 'this.confirm.createDelegate(this,["security/access/usergroup/namespace/remove"])',
            ],
        ];

        return $objectArray;
    }
}
