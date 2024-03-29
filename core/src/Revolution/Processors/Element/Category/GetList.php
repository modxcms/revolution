<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Category;

use MODX\Revolution\modCategory;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modAccessCategory;
use MODX\Revolution\Processors\Model\GetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Grabs a list of Categories.
 *
 * @property integer $start (optional) The record to start at. Defaults to 0.
 * @property integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @property string  $sort  (optional) The column to sort by. Defaults to category.
 * @property string  $dir   (optional) The direction of the sort. Defaults to ASC.
 *
 * @package MODX\Revolution\Processors\Element\Category
 */
class GetList extends GetListProcessor
{
    public $classKey = modCategory::class;
    public $languageTopics = ['category'];
    public $defaultSortField = 'category';

    /** @param boolean $isGridFilter Indicates the target of this list data is a filter field */
    protected $isGridFilter = false;

    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'showNone' => false,
        ]);
        $this->isGridFilter = $this->getProperty('isGridFilter', false);

        return $initialized;
    }

    public function beforeIteration(array $list)
    {
        if ($this->getProperty('showNone', false) && !$this->isGridFilter) {
            $list = [
                '0' => [
                    'id' => 0,
                    'category' => $this->modx->lexicon('none'),
                    'name' => $this->modx->lexicon('none'),
                ],
            ];
        }

        return $list;
    }

    public function iterate(array $data)
    {
        $list = [];

        if (!$this->getProperty('id', '')) {
            $list = $this->beforeIteration($list);

            /** @var modCategory $category */
            foreach ($data['results'] as $category) {
                if (!$category->checkPolicy('list')) {
                    continue;
                }

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
                $categoryArray = [];
            }

            $list[] = $categoryArray;
        }

        return $list;
    }

    /**
     * @param array                      $list
     * @param modCategory[]|xPDOObject[] $children
     * @param string                     $nestedName
     */
    public function includeCategoryChildren(&$list, $children, $nestedName)
    {
        if ($children && !$this->isGridFilter) {
            foreach ($children as $child) {
                if (!$child->checkPolicy('list')) {
                    continue;
                }

                $categoryArray = $child->toArray();
                $categoryArray['name'] = $nestedName . ' — ' . $child->get('category');

                $list[] = $categoryArray;

                $this->includeCategoryChildren($list, $child->Children, $categoryArray['name']);
            }
        }
    }

    /**
     * @param modCategory|xPDOObject $parent
     * @param string                 $parentName
     *
     * @return string
     */
    public function includeCategoryParent($parent, $parentName)
    {
        if (!$this->isGridFilter) {
            if ($parent) {
                $categoryName = $parent->get('category');

                return $this->includeCategoryParent($parent->Parent, $categoryName . ' — ' . $parentName);
            } else {
                return $parentName;
            }
        }
    }

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        /*
            When this class is used to fetch data for a grid filter's store (combo),
            limit results to only those categories present in the current grid.
        */
        if ($this->isGridFilter) {
            if ($userGroup = $this->getProperty('usergroup', false)) {
                $c->innerJoin(
                    modAccessCategory::class,
                    'modAccessCategory',
                    [
                        '`modAccessCategory`.`target` = `modCategory`.`id`',
                        '`modAccessCategory`.`principal` = ' . (int)$userGroup,
                        '`modAccessCategory`.`principal_class` = ' . $this->modx->quote(modUserGroup::class)
                    ]
                );
            }
            if ($policy = $this->getProperty('policy', false)) {
                $c->where([
                    '`modAccessCategory`.`policy`' => (int)$policy
                ]);
            }
        } else {
            if (!$this->getProperty('id', '')) {
                $c->where([
                    'modCategory.parent' => 0,
                ]);
            }
        }
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        if (!$this->isGridFilter && $this->getProperty('sort') == 'category') {
            $c->sortby('parent', $this->getProperty('dir', 'ASC'));
        }
        $id = $this->getProperty('id', '');
        if (!empty($id)) {
            $c->where([
                $c->getAlias() . '.id:IN' => is_string($id) ? explode(',', $id) : $id,
            ]);
        }

        return $c;
    }
}
