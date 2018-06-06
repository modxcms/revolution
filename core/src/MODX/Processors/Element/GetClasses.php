<?php

namespace MODX\Processors\Element;

use MODX\modClassMap;
use MODX\Processors\modProcessor;

/**
 * Outputs a list of Element subclasses
 *
 * @deprecated Use $modx->getDescendants($className) now
 *
 * @package modx
 * @subpackage processors.element
 */
class GetClasses extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_element');
    }


    public function initialize()
    {
        $this->setDefaultProperties([
            'limit' => 10,
            'start' => 0,
            'sort' => 'class',
            'dir' => 'ASC',
        ]);

        return true;
    }


    public function process()
    {
        $limit = $this->getProperty('limit', 10);
        $isLimit = !empty($limit);

        /* build query */
        $c = $this->modx->newQuery('modClassMap');
        $c->where([
            'parent_class' => 'modElement',
            'class:!=' => 'modTemplate',
        ]);
        $c->sortby($this->getProperty('sort'), $this->getProperty('dir'));
        if ($isLimit) $c->limit($limit, $this->getProperty('start'));
        $classes = $this->modx->getCollection('modClassMap', $c);

        /* iterate */
        $list = [];
        /** @var modClassMap $class */
        foreach ($classes as $class) {
            $el = ['name' => $class->get('class')];
            $list[] = $el;
        }

        return $this->outputArray($list);
    }
}