<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Updates a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
class modPropertySetUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modPropertySet';
    public $languageTopics = array('propertyset','category');
    public $permission = 'save_propertyset';
    public $objectType = 'propertyset';


    public function beforeSet() {
        $name = $this->getProperty('name');
        if ($this->alreadyExists($name)) {
            $this->addFieldError('name',$this->modx->lexicon('propertyset_err_ns_name'));
        }
        $name = $this->stripInvalidCharacters($name);
        $this->setProperty('name',$name);

        $category = $this->getProperty('category');
        if (!empty($category)) {
            /** @var modCategory $category */
            $category = $this->modx->getObject('modCategory',$category);
            if (empty($category)) {
                $this->addFieldError('category',$this->modx->lexicon('category_err_nf'));
            }
        } else {
            $this->setProperty('category',0);
        }

        return parent::beforeSet();
    }

    public function stripInvalidCharacters($name) {
        $invalidCharacters = array('!','@','?','`','&','&amp;');
        $name = str_replace($invalidCharacters,'',$name);
        return $name;
    }

    public function alreadyExists($name) {
        return $this->modx->getCount($this->classKey,array(
            'name' => $name,
            'id:!=' => $this->getProperty('id'),
        )) > 0;
    }
}
return 'modPropertySetUpdateProcessor';
