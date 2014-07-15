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
 class modUserGroupAccessSourceGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'sources.modAccessMediaSource';
    public $languageTopics = array('access','source');
    public $permission = 'access_permissions';
    public $defaultSortField = 'target';
    public $defaultSortDirection = 'ASC';
    /** @var modUserGroup $userGroup */
    public $userGroup;

    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'usergroup' => 0,
            'source' => false,
            'policy' => false,
        ));
        $usergroup = $this->getProperty('usergroup',false);
        if (!empty($usergroup)) {
            $this->userGroup = $this->modx->getObject('modUserGroup',$usergroup);
        }
        return $initialized;
    }

    public function getSortClassKey() {
        return 'modAccessMediaSource';
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $userGroup = $this->getProperty('usergroup');
        $c->where(array(
            'principal_class' => 'modUserGroup',
            'principal' => $userGroup,
        ));
        $source = $this->getProperty('source',false);
        if (!empty($source)) {
            $c->where(array('target' => $source));
        }
        $policy = $this->getProperty('policy',false);
        if (!empty($policy)) {
            $c->where(array('policy' => $policy));
        }
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->leftJoin('sources.modMediaSource','Target');
        $c->leftJoin('modUserGroupRole','Role',array(
            'Role.authority = modAccessMediaSource.authority',
        ));
        $c->leftJoin('modAccessPolicy','Policy');
        $c->select($this->modx->getSelectColumns('sources.modAccessMediaSource','modAccessMediaSource'));
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
                'text' => $this->modx->lexicon('access_source_update'),
                'handler' => 'this.updateAcl',
            ),
            '-',
            array(
                'text' => $this->modx->lexicon('access_source_remove'),
                'handler' => 'this.confirm.createDelegate(this,["security/access/usergroup/source/remove"])',
            ),
        );
        return $objectArray;
    }
}
return 'modUserGroupAccessSourceGetListProcessor';