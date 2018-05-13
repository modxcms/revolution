<?php

namespace MODX\Processors\Element\PropertySet;

use MODX\modCategory;
use MODX\Processors\modObjectUpdateProcessor;

/**
 * Updates a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
class Update extends modObjectUpdateProcessor
{
    public $classKey = 'modPropertySet';
    public $languageTopics = ['propertyset', 'category'];
    public $permission = 'save_propertyset';
    public $objectType = 'propertyset';


    public function beforeSet()
    {
        $name = $this->getProperty('name');
        if ($this->alreadyExists($name)) {
            $this->addFieldError('name', $this->modx->lexicon('propertyset_err_ns_name'));
        }
        $name = $this->stripInvalidCharacters($name);
        $this->setProperty('name', $name);

        $category = $this->getProperty('category');
        if (!empty($category)) {
            /** @var modCategory $category */
            $category = $this->modx->getObject('modCategory', $category);
            if (empty($category)) {
                $this->addFieldError('category', $this->modx->lexicon('category_err_nf'));
            }
        } else {
            $this->setProperty('category', 0);
        }

        return parent::beforeSet();
    }


    public function stripInvalidCharacters($name)
    {
        $invalidCharacters = ['!', '@', '?', '`', '&', '&amp;'];
        $name = str_replace($invalidCharacters, '', $name);

        return $name;
    }


    public function alreadyExists($name)
    {
        return $this->modx->getCount($this->classKey, [
                'name' => $name,
                'id:!=' => $this->getProperty('id'),
            ]) > 0;
    }
}