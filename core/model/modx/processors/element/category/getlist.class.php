<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Grabs a list of Categories.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to category.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.category
 */
class modElementCategoryGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modCategory';
    public $languageTopics = array('category');
    public $defaultSortField = 'category';
    public $permission = 'view_category';

    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'showNone' => false,
        ));

        return $initialized;
    }

    public function beforeIteration(array $list) {
        if ($this->getProperty('showNone',false)) {
            $list = array('0' => array(
                'id' => 0,
                'category' => $this->modx->lexicon('none'),
                'name' => $this->modx->lexicon('none'),
            ));
        }

        return $list;
    }

    public function iterate(array $data) {
        $list = array();

        if (!$this->getProperty('id', '')) {
            $list = $this->beforeIteration($list);

            /** @var modCategory $category */
            foreach ($data['results'] as $category) {
                if (!$category->checkPolicy('list')) continue;

                $categoryArray = $category->toArray();
                $categoryArray['name'] = $category->get('category');

                $list[] = $categoryArray;

                $this->includeCategoryChildren($list, $category->Children, $categoryArray['name']);
            }

            $list = $this->afterIteration($list);
        } else {
            $category = array_shift($data['results']);

            if ($category) {
                $categoryArray = $category->toArray();
                $categoryName = $category->get('category');

                $categoryArray['name'] = $this->includeCategoryParent($category->Parent, $categoryName);
            } else {
                $categoryArray = array();
            }

            $list[] = $categoryArray;
        }

        return $list;
    }

    /**
     * @param array $list
     * @param modCategory[]|xPDOObject[] $children
     * @param string $nestedName
     */
    public function includeCategoryChildren(&$list, $children, $nestedName) {
        if ($children) {
            foreach ($children as $child) {
                if (!$child->checkPolicy('list')) continue;

                $categoryArray = $child->toArray();
                $categoryArray['name'] = $nestedName . ' — ' . $child->get('category');

                $list[] = $categoryArray;

                $this->includeCategoryChildren($list, $child->Children, $categoryArray['name']);
            }
        }
    }

    /**
     * @param modCategory|xPDOObject $parent
     * @param string $parentName
     * @return string
     */
    public function includeCategoryParent($parent, $parentName) {
        if ($parent) {
            $categoryName = $parent->get('category');
            return $this->includeCategoryParent($parent->Parent, $categoryName . ' — ' . $parentName);
        } else {
            return $parentName;
        }
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        if (!$this->getProperty('id', '')) {
            $c->where(array(
                'modCategory.parent' => 0,
            ));
        }

        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        if ($this->getProperty('sort') == 'category') {
            $c->sortby('parent',$this->getProperty('dir','ASC'));
        }
        $id = $this->getProperty('id','');
        if (!empty($id)) {
            $c->where(array(
                $this->classKey . '.id:IN' => is_string($id) ? explode(',', $id) : $id,
            ));
        }
        return $c;
    }
}
return 'modElementCategoryGetListProcessor';
