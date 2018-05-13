<?php

namespace MODX\Processors\Element\Category;

use MODX\modCategory;
use MODX\Processors\modObjectGetListProcessor;
use xPDO\Om\xPDOQuery;

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
class GetList extends modObjectGetListProcessor
{
    public $classKey = 'modCategory';
    public $languageTopics = ['category'];
    public $defaultSortField = 'category';


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
                'id' => 0,
                'category' => $this->modx->lexicon('none'),
                'name' => $this->modx->lexicon('none'),
            ]];
        }

        return $list;
    }


    public function iterate(array $data)
    {
        $list = [];
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

        return $list;
    }


    public function includeCategoryChildren(&$list, $children, $nestedName)
    {
        if ($children) {
            /** @var modCategory $child */
            foreach ($children as $child) {
                if (!$child->checkPolicy('list')) continue;

                $categoryArray = $child->toArray();
                $categoryArray['name'] = $nestedName . ' — ' . $child->get('category');

                $list[] = $categoryArray;

                $this->includeCategoryChildren($list, $child->Children, $categoryArray['name']);
            }
        }
    }


    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->where([
            'modCategory.parent' => 0,
        ]);

        return $c;
    }


    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        if ($this->getProperty('sort') == 'category') {
            $c->sortby('parent', $this->getProperty('dir', 'ASC'));
        }

        return $c;
    }
}
