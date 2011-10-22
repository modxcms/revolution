<?php
/**
 * Update a category.
 *
 * @param integer $id The ID of the category.
 * @param string $category The new name of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
class modElementCategoryUpdateProcessor extends modProcessor {
    /** @var modCategory $category */
    public $category;

    public function initialize() {
        $id = $this->getProperty('id',null);
        if (empty($id)) return $this->failure($this->modx->lexicon('category_err_ns'));
        $this->category = $this->modx->getObject('modCategory',$id);
        return empty($this->category) ? $this->modx->lexicon('category_err_nf') : true;
    }

    public function checkPermissions() {
        return $this->modx->hasPermission('save_category');
    }

    public function getLanguageTopics() {
        return array('category');
    }
    
    public function process() {
        if (!$this->category->checkPolicy('save')) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        /* set fields */
        $this->category->fromArray($this->getProperties());

        /* save category */
        if ($this->category->save() === false) {
            return $this->failure($this->modx->lexicon('category_err_save'));
        } else {
            /* log manager action */
            $this->modx->logManagerAction('category_update','modCategory',$this->category->get('id'));
        }

        return $this->success('',$this->category);
    }
}
return 'modElementCategoryUpdateProcessor';