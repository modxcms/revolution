<?php

namespace MODX\Processors\Security\Access\Permission;

use MODX\Processors\modObjectGetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * @package modx
 * @subpackage processors.security.permission
 */
class GetList extends modObjectGetListProcessor
{
    public $checkListPermission = false;
    public $objectType = 'permission';
    public $classKey = 'modAccessPermission';
    public $permission = 'access_permissions';
    public $languageTopics = ['access', 'permission'];


    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'query' => '',
        ]);

        return $initialized;
    }


    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin('modAccessPolicyTemplate', 'Template');
        $c->query['DISTINCT'] = 'DISTINCT';
        $query = $this->getProperty('query', '');
        if (!empty($query)) {
            $c->where([
                'modAccessPermission.name:LIKE' => '%' . $query . '%',
            ]);
        }

        return $c;
    }


    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select([
            'modAccessPermission.id',
            'modAccessPermission.name',
            'modAccessPermission.description',
            'Template.lexicon',
        ]);
        $c->groupby('modAccessPermission.name');

        return $c;
    }


    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->get(['name', 'description']);

        $lexicon = $object->get('lexicon');
        if (!empty($lexicon)) {
            if (strpos($lexicon, ':') !== false) {
                $this->modx->lexicon->load($lexicon);
            } else {
                $this->modx->lexicon->load('core:' . $lexicon);
            }
        }
        $objectArray['description'] = $this->modx->lexicon($objectArray['description']);

        return $objectArray;
    }
}
