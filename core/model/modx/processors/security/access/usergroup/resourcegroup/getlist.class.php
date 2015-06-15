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
 * @subpackage processors.security.access.usergroup.resourcegroup
 */
 class modUserGroupAccessResourceGroupGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modAccessResourceGroup';
    public $languageTopics = array('access');
    public $permission = 'access_permissions';
    public $defaultSortField = 'target';
    public $defaultSortDirection = 'ASC';
    /** @var modUserGroup $userGroup */
    public $userGroup;

    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'usergroup' => 0,
            'resourceGroup' => false,
            'policy' => false,
        ));
        $usergroup = $this->getProperty('usergroup',false);
        if (!empty($usergroup)) {
            $this->userGroup = $this->modx->getObject('modUserGroup',$usergroup);
        }
        return $initialized;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $userGroup = $this->getProperty('usergroup');
        $c->where(array(
            'principal_class' => 'modUserGroup',
            'principal' => $userGroup,
        ));
        $resourceGroup = $this->getProperty('resourceGroup',false);
        if (!empty($resourceGroup)) {
            $c->where(array('target' => $resourceGroup));
        }
        $policy = $this->getProperty('policy',false);
        if (!empty($policy)) {
            $c->where(array('policy' => $policy));
        }
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->leftJoin('modResourceGroup','Target');
        $c->leftJoin('modUserGroupRole','Role',array(
            'Role.authority = modAccessResourceGroup.authority',
        ));
        $c->leftJoin('modAccessPolicy','Policy');
        $c->select($this->modx->getSelectColumns('modAccessResourceGroup','modAccessResourceGroup'));
        $c->select(array(
            'name' => 'Target.name',
            'role_name' => 'Role.name',
            'policy_name' => 'Policy.name',
            'policy_data' => 'Policy.data',
        ));
        return $c;
    }

    /**
     * @param xPDOObject|modAccessResourceGroup $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        if (empty($objectArray['name'])) $objectArray['name'] = '('.$this->modx->lexicon('none').')';
        $objectArray['authority_name'] = !empty($objectArray['role_name']) ? $objectArray['role_name'].' - '.$objectArray['authority'] : $objectArray['authority'];

        /* get permissions list */
        $data = $objectArray['policy_data'];
        unset($objectArray['policy_data']);
        $data = $this->modx->fromJSON($data);
        if (!empty($data)) {
            $permissions = array();
            foreach ($data as $perm => $v) {
                $permissions[] = $perm;
            }
            $objectArray['permissions'] = implode(', ',$permissions);
        }

        $cls = '';
        if (    ($objectArray['target'] == 'web' || $objectArray['target'] == 'mgr')
                && $objectArray['policy_name'] == 'Administrator'
                && ($this->userGroup && $this->userGroup->get('name') == 'Administrator')
           ) {} else {
            $cls .= 'pedit premove';
        }
        $objectArray['cls'] = $cls;
        $objectArray['menu'] = array(
            array(
                'text' => $this->modx->lexicon('access_rgroup_update'),
                'handler' => 'this.updateAcl',
            ),
            '-',
            array(
                'text' => $this->modx->lexicon('access_rgroup_remove'),
                'handler' => 'this.confirm.createDelegate(this,["security/access/usergroup/resourcegroup/remove"])',
            ),
        );
        return $objectArray;
    }
}
return 'modUserGroupAccessResourceGroupGetListProcessor';