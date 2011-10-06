<?php
/**
 * Deletes a category. Resets all elements with that category to 0.
 *
 * @param integer $id The ID of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
class modElementCategoryRemoveProcessor extends modProcessor {
    /** @var modCategory $category */
    public $category;

    public function initialize() {
        $id = $this->getProperty('id',null);
        if (empty($id)) return $this->failure($this->modx->lexicon('category_err_ns'));
        $this->category = $this->modx->getObject('modCategory',$id);
        return empty($this->category) ? $this->modx->lexicon('category_err_nf') : true;
    }

    public function checkPermissions() {
        return $this->modx->hasPermission('delete_category');
    }

    public function getLanguageTopics() {
        return array('category');
    }

    public function process() {
        if (!$this->category->checkPolicy('remove')) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        /* save category */
        if ($this->category->save() === false) {
            return $this->failure($this->modx->lexicon('category_err_save'));
        } else {
            /* log manager action */
            $this->modx->logManagerAction('category_delete','modCategory',$this->category->get('id'));
        }

        return $this->success('',$this->category);
    }
}
return 'modElementCategoryRemoveProcessor';