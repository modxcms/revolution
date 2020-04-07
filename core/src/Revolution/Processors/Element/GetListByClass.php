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


use MODX\Revolution\modElement;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modTemplate;

/**
 * Grabs a list of elements by their subclass
 *
 * @package MODX\Revolution\Processors\Element
 */
class GetListByClass extends Processor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('class_map');
    }

    public function getLanguageTopics()
    {
        return ['propertyset', 'element'];
    }

    public function initialize()
    {
        $this->setDefaultProperties([
            'limit' => 10,
            'start' => 0,
            'sort' => 'name',
            'dir' => 'ASC',
            'element_class' => false,
        ]);

        return true;
    }

    public function process()
    {
        $className = $this->getProperty('element_class');
        if (empty($className)) {
            return $this->failure($this->modx->lexicon('element_class_ns'));
        }
        $data = $this->getElements($className);

        $list = [];
        /** @var modElement $element */
        foreach ($data['results'] as $element) {
            $elementArray = $element->toArray();
            $elementArray['name'] = isset($elementArray['templatename']) ? $elementArray['templatename'] : $elementArray['name'];
            $list[] = $elementArray;
        }

        return $this->outputArray($list, $data['total']);
    }

    public function getElements($className)
    {
        /* get default properties */
        $limit = $this->getProperty('limit', 10);
        $sort = $this->getProperty('sort', 'name');
        $isLimit = !empty($limit);
        /* fix for template's different name field */
        if ($className == modTemplate::class && $sort == 'name') {
            $sort = 'templatename';
        }

        $c = $this->modx->newQuery($className);
        $data['total'] = $this->modx->getCount($className, $c);

        $c->sortby($sort, $this->getProperty('dir', 'ASC'));
        $id = $this->getProperty('id', '');
        if (!empty($id)) {
            $c->where([
                'id:IN' => is_string($id) ? explode(',', $id) : $id,
            ]);
        }
        if ($isLimit) {
            $c->limit($limit, $this->getProperty('start', 0));
        }
        $data['results'] = $this->modx->getCollection($className, $c);

        return $data;
    }
}
