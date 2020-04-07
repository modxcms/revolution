<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\PropertySet;


use MODX\Revolution\modElement;
use MODX\Revolution\modElementPropertySet;
use MODX\Revolution\Processors\Model\DuplicateProcessor;
use MODX\Revolution\modPropertySet;

/**
 * Duplicates a property set
 *
 * @package MODX\Revolution\Processors\Element\PropertySet
 */
class Duplicate extends DuplicateProcessor
{
    public $classKey = modPropertySet::class;
    public $objectType = 'propertyset';
    public $permission = 'new_propertyset';
    public $languageTopics = ['propertyset', 'category'];

    public function beforeSave()
    {
        $copyEls = (bool)$this->getProperty('copyels', false);
        if ($copyEls) {
            $els = $this->object->getMany('Elements');
            $pses = [];
            /** @var modElement $el */
            foreach ($els as $el) {
                $pse = $this->modx->newObject(modElementPropertySet::class);
                $pse->set('element_class', $el->get('element_class'));
                $pse->set('element', $el->get('element'));
                $pses[] = $pse;
            }
            $this->newObject->addMany($pses);
        }

        return parent::beforeSave();
    }
}
