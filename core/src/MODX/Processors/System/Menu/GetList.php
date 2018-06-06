<?php

namespace MODX\Processors\System\Menu;

use MODX\Processors\modObjectGetListProcessor;
use xPDO\Om\xPDOObject;

/**
 * Get a list of menu items
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to menuindex.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.menu
 */
class GetList extends modObjectGetListProcessor
{
    public $classKey = 'modMenu';
    public $objectType = 'menu';
    public $primaryKeyField = 'text';
    public $languageTopics = ['action', 'menu', 'topmenu'];
    public $permission = 'menus';
    public $defaultSortField = 'menuindex';


    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'showNone' => false,
        ]);

        return $initialized;
    }


    public function beforeIteration(array $list)
    {
        if ($this->getProperty('showNone', false)) {
            $list = ['0' => [
                'text' => '',
                'text_lex' => "({$this->modx->lexicon('none')})",
            ]];
        }

        return $list;
    }


    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        $namespace = $object->get('namespace');
        if (!in_array($namespace, ['core', '', null])) {
            $this->modx->lexicon->load($namespace . ':default');
        }
        $objectArray['text_lex'] = $this->modx->lexicon($objectArray['text']);

        return $objectArray;
    }
}
