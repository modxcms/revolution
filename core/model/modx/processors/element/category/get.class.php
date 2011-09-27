<?php
/**
 * Gets a category.
 *
 * @param integer $id The ID of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
class modElementCategoryGet extends modProcessor {
    /** @var modCategory $category */
    public $category;

    public function initialize() {
        $id = $this->getProperty('id',null);
        if (empty($id)) return $this->failure($this->modx->lexicon('category_err_ns'));
        $this->category = $this->modx->getObject('modCategory',$id);
        return empty($this->category) ? $this->modx->lexicon('category_err_nf') : true;
    }

    public function checkPermissions() {
        return $this->modx->hasPermission('view_category');
    }

    public function getLanguageTopics() {
        return array('category');
    }

    public function process() {
        if (!$this->category->checkPolicy('view')) return $this->failure($this->modx->lexicon('access_denied'));

        return $this->success('',$this->category);
    }
}
return 'modElementCategoryGet';