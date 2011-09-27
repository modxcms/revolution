<?php
/**
 * Create a category.
 *
 * @param string $category The new name of the category.
 *
 * @package modx
 * @subpackage processors.element.category
 */
class modElementCategoryCreate extends modProcessor {
    /** @var modCategory $category */
    public $category;

    public function checkPermissions() {
        return $this->modx->hasPermission('save_category');
    }

    public function getLanguageTopics() {
        return array('category');
    }

    public function process() {
        if (!$this->validate()) {
            return $this->failure();
        }

        /* set fields */
        $this->category = $this->modx->newObject('modCategory');
        $this->category->fromArray($this->getProperties());

        /* save category */
        if ($this->category->save() === false) {
            $this->modx->error->checkValidation($this->category);
            return $this->failure($this->modx->lexicon('category_err_create'));
        } else {
            /* log manager action */
            $this->modx->logManagerAction('category_create','modCategory',$this->category->get('id'));
        }

        return $this->success('',$this->category);
    }

    /**
     * Validate the creation
     * @return array|string
     */
    public function validate() {
        $name = $this->getProperty('category');
        if (empty($name)) {
            $this->modx->error->addField('category',$this->modx->lexicon('category_err_ns'));
        } else if ($this->alreadyExists($name)) {
            $this->modx->error->addField('category',$this->modx->lexicon('category_err_ae'));
        }

        return !$this->modx->error->hasError();
    }

    /**
     * Check to see if a Category with that name already exists
     * 
     * @param string $name The name to check against
     * @return null|modCategory
     */
    public function alreadyExists($name) {
        return $this->modx->getCount('modCategory',array('category' => $name)) > 0;
    }
}
return 'modElementCategoryCreate';