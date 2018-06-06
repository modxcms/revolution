<?php

namespace MODX\Processors\System\ActiveResource;

use MODX\Processors\modObjectGetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of active resources
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @param string $dateFormat (optional) The strftime date format to format the
 * editedon date to. Defaults to: %b %d, %Y %I:%M %p
 *
 * @package modx
 * @subpackage processors.system.activeresource
 */
class GetList extends modObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $languageTopics = ['resource'];
    public $permission = 'view_document';
    public $defaultSortField = 'editedon';
    public $defaultSortDirection = 'DESC';


    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_document');
    }


    public function getLanguageTopics()
    {
        return ['resource'];
    }


    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'dateFormat' => '%b %d, %Y %I:%M %p',
        ]);

        return $initialized;
    }


    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->innerJoin('modUser', 'EditedBy');
        $c->where([
            'deleted' => 0,
            'editedon:!=' => null,
        ]);

        return $c;
    }


    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns('modResource', 'modResource'));
        $c->select($this->modx->getSelectColumns('modUser', 'EditedBy', '', ['username']));

        return $c;
    }


    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->get([
            'id', 'pagetitle', 'editedon', 'username',
        ]);
        $objectArray['editedon'] = strftime($this->getProperty('dateFormat'), strtotime($object->get('editedon')));

        return $objectArray;
    }
}
