<?php
/**
 * Abstract class for Update Element processors. To be extended for each derivative element type.
 *
 * @abstract
 * @package modx
 * @subpackage processors.element
 */
abstract class modElementUpdateProcessor extends modObjectUpdateProcessor {
    public $previousCategory;

    public function beforeSave() {
        $locked = $this->getProperty('locked',false);
        $this->object->set('locked',(boolean)$locked);

        /* make sure a name was specified */
        $nameField = $this->classKey == 'modTemplate' ? 'templatename' : 'name';
        $name = $this->getProperty($nameField,'');
        if (empty($name)) {
            $this->addFieldError($nameField,$this->modx->lexicon($this->objectType.'_err_ns_name'));
        } else if ($this->alreadyExists($name)) {
            /* if changing name, but new one already exists */
            $this->modx->error->addField($nameField,$this->modx->lexicon($this->objectType.'_err_exists_name',array('name' => $name)));
        }

        /* if element is locked */
        if ($this->object->get('locked') && $this->modx->hasPermission('edit_locked') == false) {
            $this->addFieldError($nameField,$this->modx->lexicon($this->objectType.'_err_locked'));
        }

        /* category */
        $category = $this->object->get('category');
        $this->previousCategory = $category;
        if (!empty($category)) {
            $category = $this->modx->getObject('modCategory',array('id' => $category));
            if (empty($category)) {
                $this->addFieldError('category',$this->modx->lexicon('category_err_nf'));
            }
        }

        /* can't change content if static source is not writable */
        if ($this->object->staticContentChanged()) {
            if (!$this->object->isStaticSourceMutable()) {
                $this->addFieldError('static_file', $this->modx->lexicon('element_static_source_immutable'));
            }
        }

        return !$this->hasErrors();
    }

    public function alreadyExists($name) {
        $nameField = $this->classKey == 'modTemplate' ? 'templatename' : 'name';
        return $this->modx->getCount($this->classKey,array(
            'id:!=' => $this->object->get('id'),
            $nameField => $name,
        )) > 0;
    }

    public function afterSave() {
        if ($this->getProperty('clearCache',true)) {
            $this->modx->cacheManager->refresh();
        }
    }

    public function cleanup() {
        return $this->success('',array_merge($this->object->get(array('id', 'name', 'description', 'locked', 'category', 'content')), array('previous_category' => $this->previousCategory)));
    }
}