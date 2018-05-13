<?php

namespace MODX\Processors\Element\PropertySet;

use MODX\modCategory;
use MODX\Processors\modObjectCreateProcessor;

/**
 * Creates a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
class Create extends modObjectCreateProcessor
{
    public $classKey = 'modPropertySet';
    public $languageTopics = ['propertyset'];
    public $permission = 'new_propertyset';
    public $objectType = 'propertyset';


    public function beforeSet()
    {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('propertyset_err_ns_name'));
        } elseif ($this->doesAlreadyExist(['name' => $name])) {
            $this->addFieldError('name', $this->modx->lexicon('propertyset_err_ae'));
        }

        return parent::beforeSet();
    }


    public function beforeSave()
    {
        /* set category if specified */
        $category = $this->getProperty('category', null);
        if (!empty($category)) {
            /** @var modCategory $category */
            $category = $this->modx->getObject('modCategory', $category);
            if (empty($category)) {
                $this->addFieldError('category', $this->modx->lexicon('category_err_nf'));
            } else {
                $this->object->set('category', $category->get('id'));
            }
        }
        $this->stripInvalidCharacters();

        return parent::beforeSave();
    }


    public function stripInvalidCharacters()
    {
        $invalidCharacters = ['!', '@', '?', '`', '&', '&amp;'];
        $name = $this->object->get('name');
        $name = str_replace($invalidCharacters, '', $name);
        $this->object->set('name', $name);
    }
}
