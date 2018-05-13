<?php

namespace MODX\Processors\Security\Access\Policy\Template;

use MODX\Processors\modObjectGetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of policy templates.
 *
 * @param boolean $combo (optional) If true, will append a 'no policy' row to
 * the beginning.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Default
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
class GetList extends modObjectGetListProcessor
{
    public $checkListPermission = false;
    public $objectType = 'policy_template';
    public $classKey = 'modAccessPolicyTemplate';
    public $permission = 'policy_template_view';
    public $languageTopics = ['policy'];


    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'sortAlias' => 'modAccessPolicyTemplate',
            'query' => '',
        ]);

        return $initialized;
    }


    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->innerJoin('modAccessPolicyTemplateGroup', 'TemplateGroup');
        $query = $this->getProperty('query', '');
        if (!empty($query)) {
            $c->where([
                'modAccessPolicyTemplate.name:LIKE' => '%' . $query . '%',
                'OR:modAccessPolicyTemplate.description:LIKE' => '%' . $query . '%',
            ]);
        }

        return $c;
    }


    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $subQuery = $this->modx->newQuery('modAccessPermission');
        $subQuery->select('COUNT(modAccessPermission.id)');
        $subQuery->where([
            'modAccessPermission.template = modAccessPolicyTemplate.id',
        ]);
        $subQuery->prepare();
        $c->select($this->modx->getSelectColumns('modAccessPolicyTemplate', 'modAccessPolicyTemplate'));
        $c->select([
            'template_group_name' => 'TemplateGroup.name',
        ]);
        $c->select('(' . $subQuery->toSql() . ') AS ' . $this->modx->escape('total_permissions'));

        return $c;
    }


    public function prepareRow(xPDOObject $object)
    {
        $core = ['ResourceTemplate', 'ObjectTemplate', 'AdministratorTemplate', 'ElementTemplate'];

        $objectArray = $object->toArray();
        $cls = ['pedit'];
        if (!in_array($object->get('name'), $core)) {
            $cls[] = 'premove';
        }
        $objectArray['cls'] = implode(' ', $cls);

        return $objectArray;
    }
}
