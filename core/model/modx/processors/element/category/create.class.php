<?php
/**
 * Create a category.
 *
 * @param string $category The new name of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
class modElementCategoryCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modCategory';
    public $languageTopics = array('category');
    public $permission = 'save_category';
    public $objectType = 'category';

    /**
     * Validate the creation
     * @return boolean
     */
    public function beforeSave() {
        $name = $this->getProperty('category');
        if (empty($name)) {
            $this->addFieldError('category',$this->modx->lexicon('category_err_ns'));
        } else if ($this->alreadyExists($name)) {
            $this->addFieldError('category',$this->modx->lexicon('category_err_ae'));
        }

        return !$this->hasErrors();
    }

    /**
     * Check to see if a Category with that name already exists
     * 
     * @param string $name The name to check against
     * @return boolean
     */
    public function alreadyExists($name) {
        return $this->modx->getCount('modCategory',array('category' => $name)) > 0;
    }
}
return 'modElementCategoryCreateProcessor';