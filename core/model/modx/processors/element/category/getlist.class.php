<?php
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
class modElementCategoryGetList extends modProcessor {

    public function getLanguageTopics() {
        return array('category');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 20,
            'sort' => 'parent,category',
            'dir' => 'ASC',
            'showNone' => false,
        ));
        return true;
    }

    public function process() {
        $categories = $this->getCategories();

        $list = array();
        if ($this->getProperty('showNone',false)) {
            $list = array('0' => array(
                'id' => '',
                'category' => $this->modx->lexicon('none'),
                'name' => $this->modx->lexicon('none'),
            ));
        }

        /* iterate through categories */
        /** @var modCategory $category */
        foreach ($categories as $category) {
            if (!$category->checkPolicy('list')) continue;
            $categoryArray = $category->toArray();

            $childrenCount = $this->modx->getCount('modCategory',array('parent' => $category->get('id')));

            $categoryArray['name'] = $category->get('category');
            $list[] = $categoryArray;

            /* if has subcategories, display here */
            if ($childrenCount > 0) {
                $c = $this->modx->newQuery('modCategory');
                $c->where(array('parent' => $category->get('id')));
                $c->sortby('category','ASC');
                $children = $category->getMany('Children',$c);
                /** @var modCategory $subcat */
                foreach ($children as $subcat) {
                    $categoryArray = $subcat->toArray();
                    $categoryArray['name'] = $category->get('category').' - '.$subcat->get('category');
                    $list[] = $categoryArray;
                }
            }
        }

        return $this->outputArray($list);
    }

    /**
     * Get all categories
     * 
     * @return array|xPDOIterator
     */
    public function getCategories() {
        $limit = $this->getProperty('limit');
        $isLimit = !empty($limit);
        $c = $this->modx->newQuery('modCategory');
        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        $c->where(array(
            'modCategory.parent' => 0,
        ));
        if ($isLimit) $c->limit($limit,$this->getProperty('start'));
        return $this->modx->getIterator('modCategory',$c);
    }
}
return 'modElementCategoryGetList';