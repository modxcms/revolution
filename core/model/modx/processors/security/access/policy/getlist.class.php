<?php
/**
 * Gets a list of policies.
 *
 * @param boolean $combo (optional) If true, will append a 'no policy' row to
 * the beginning.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @var modX $this->modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
class modAccessPolicyGetListProcessor extends modObjectGetListProcessor {
    public $checkListPermission = false;
    public $objectType = 'policy';
    public $classKey = 'modAccessPolicy';
    public $permission = 'policy_view';
    public $languageTopics = array('policy');

    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'sortAlias' => 'modAccessPolicy',
            'group' => false,
            'combo' => false,
            'query' => '',
        ));
        return $initialized;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->innerJoin('modAccessPolicyTemplate','Template');
        $group = $this->getProperty('group');
        if (!empty($group)) {
            $group = is_array($group) ? $group : explode(',',$group);
            $c->innerJoin('modAccessPolicyTemplateGroup','TemplateGroup','TemplateGroup.id = Template.template_group');
            $c->where(array(
                'TemplateGroup.name:IN' => $group,
            ));
        }
        $query = $this->getProperty('query','');
        if (!empty($query)) {
            $c->where(array(
                'modAccessPolicy.name:LIKE' => '%'.$query.'%',
                'OR:modAccessPolicy.description:LIKE' => '%'.$query.'%'
            ));
        }
        return $c;
    }
    
    public function prepareQueryAfterCount(xPDOQuery $c) {
        $subc = $this->modx->newQuery('modAccessPermission');
        $subc->select('COUNT(modAccessPermission.id)');
        $subc->where(array(
            'modAccessPermission.template = Template.id',
        ));
        $subc->prepare();
        $c->select($this->modx->getSelectColumns('modAccessPolicy','modAccessPolicy'));
        $c->select(array(
            'template_name' => 'Template.name',
        ));
        $c->select('('.$subc->toSql().') AS '.$this->modx->escape('total_permissions'));
        return $c;
    }

    public function beforeIteration(array $list) {
        if ($this->getProperty('combo',false)) {
            $list[] = array(
                'id' => '',
                'name' => $this->modx->lexicon('no_policy_option'),
            );
        }
        return $list;
    }

    public function prepareRow(xPDOObject $object) {
        $core = array('Resource','Object','Administrator','Load Only','Load, List and View');
        $policyArray = $object->toArray();
        $permissions = array();
        $cls = 'pedit';
        if (!in_array($object->get('name'),$core)) {
            $cls .= ' premove';
        }
        $policyArray['cls'] = $cls;
        if (!empty($policyArray['total_permissions'])) {
            $data = $object->get('data');
            $ct = 0;
            if (!empty($data)) {
                foreach ($data as $k => $v) {
                    if (!empty($v)) {
                        $permissions[] = $k;
                        $ct++;
                    }
                }
            }
            $policyArray['active_permissions'] = $ct;
            $policyArray['active_of'] = $this->modx->lexicon('active_of',array(
                'active' => $policyArray['active_permissions'],
                'total' => $policyArray['total_permissions'],
            ));
            $policyArray['permissions'] = $permissions;
        }

        unset($policyArray['data']);
        return $policyArray;
    }
}
return 'modAccessPolicyGetListProcessor';