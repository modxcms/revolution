<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element;


use MODX\Revolution\modClassMap;
use MODX\Revolution\modProcessor;

/**
 * Outputs a list of Element subclasses
 *
 * @deprecated Use $modx->getDescendants($className) now
 *
 * @package    MODX\Revolution\Processors\Element
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
        $this->modx->deprecated('2.1.4', 'Please use $modx->getDescendants($className) now.',
            'modElementGetClassesProcessor support');

        $limit = $this->getProperty('limit', 10);
        $isLimit = !empty($limit);

        /* build query */
        $c = $this->modx->newQuery(modClassMap::class);
        $c->where([
            'parent_class' => 'modElement',
            'class:!=' => 'modTemplate',
        ]);
        $c->sortby($this->getProperty('sort'), $this->getProperty('dir'));
        $name = $this->getProperty('name', '');
        if (!empty($name)) {
            $c->where([
                'modClassMap.name:IN' => is_string($name) ? explode(',', $name) : $name,
            ]);
        }
        if ($isLimit) {
            $c->limit($limit, $this->getProperty('start'));
        }
        $classes = $this->modx->getCollection(modClassMap::class, $c);

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
