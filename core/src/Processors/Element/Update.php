<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element;


use MODX\Revolution\modCategory;
use MODX\Revolution\modElement;
use MODX\Revolution\modObjectUpdateProcessor;
use MODX\Revolution\modTemplate;

/**
 * Abstract class for Update Element processors. To be extended for each derivative element type.
 *
 * @abstract
 *
 * @package MODX\Revolution\Processors\Element
 */
abstract class Update extends modObjectUpdateProcessor
{
    public $previousCategory;
    /** @var modElement $object */
    public $object;

    public function beforeSave()
    {
        $locked = $this->getProperty('locked');
        if (!is_null($locked)) {
            $this->object->set('locked', (boolean)$locked);
        }

        /* make sure a name was specified */
        $nameField = $this->classKey == modTemplate::class ? 'templatename' : 'name';
        $name = $this->getProperty($nameField, '');
        if (empty($name)) {
            $this->addFieldError($nameField, $this->modx->lexicon($this->objectType . '_err_ns_name'));
        } else {
            if ($this->alreadyExists($name)) {
                /* if changing name, but new one already exists */
                $this->modx->error->addField($nameField,
                    $this->modx->lexicon($this->objectType . '_err_ae', ['name' => $name]));
            }
        }

        /* if element is locked */
        if ($this->object->get('locked') && $this->modx->hasPermission('edit_locked') == false) {
            $this->addFieldError($nameField, $this->modx->lexicon($this->objectType . '_err_locked'));
        }

        /* category */
        $category = $this->object->get('category');
        $this->previousCategory = $category;
        if (!empty($category)) {
            $category = $this->modx->getObject(modCategory::class, ['id' => $category]);
            if (empty($category)) {
                $this->addFieldError('category', $this->modx->lexicon('category_err_nf'));
            }
        }

        /* can't change content if static source is not writable */
        if ($this->object->staticSourceChanged() || $this->object->staticContentChanged()) {
            if (!$this->object->isStaticSourceMutable()) {
                $source = $this->object->getSource();
                if ($source && $source->hasErrors()) {
                    $this->addFieldError('static_file', reset($source->getErrors()));
                } else {
                    $this->addFieldError('static_file', $this->modx->lexicon('element_static_source_immutable'));
                }
            } else {
                if (!$this->object->isStaticSourceValidPath()) {
                    $this->addFieldError('static_file',
                        $this->modx->lexicon('element_static_source_protected_invalid'));
                }
            }
        }

        return !$this->hasErrors();
    }

    public function alreadyExists($name)
    {
        $nameField = $this->classKey == modTemplate::class ? 'templatename' : 'name';

        return $this->modx->getCount($this->classKey, [
                'id:!=' => $this->object->get('id'),
                $nameField => $name,
            ]) > 0;
    }

    public function afterSave()
    {
        if ($this->getProperty('clearCache', true)) {
            $this->modx->cacheManager->refresh();
        }
    }

    public function cleanup()
    {
        return $this->success('',
            array_merge($this->object->get(['id', 'name', 'description', 'locked', 'category', 'content']),
                ['previous_category' => $this->previousCategory]));
    }
}

