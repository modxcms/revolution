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
use MODX\Revolution\Processors\Model\UpdateProcessor;
use MODX\Revolution\modTemplate;
use MODX\Revolution\modTemplateVar;

/**
 * Abstract class for Update Element processors. To be extended for each derivative element type.
 *
 * @abstract
 *
 * @package MODX\Revolution\Processors\Element
 */
abstract class Update extends UpdateProcessor
{
    public $previousCategory;
    /** @var modElement $object */
    public $object;

    protected $elementNameField = 'name';

    public function initialize()
    {
        if ($this->classKey === modTemplate::class) {
            $this->elementNameField = 'templatename';
        }
        return parent::initialize();
    }

    public function beforeSet()
    {
        // Make sure the element isn't locked
        if ($this->object->get('locked') && !$this->modx->hasPermission('edit_locked')) {
            return $this->modx->lexicon($this->objectType . '_err_locked');
        }

        return parent::beforeSet();
    }

    public function beforeSave()
    {
        $locked = $this->getProperty('locked');
        if (!is_null($locked)) {
            $this->object->set('locked', (bool)$locked);
        }

        $isTV = $this->classKey === modTemplateVar::class;

        if ($isTV) {
            if ($caption = trim($this->getProperty('caption', ''))) {
                $caption = $this->modx->stripHtml(
                    $caption,
                    $this->modx->getOption('elements_caption_allowedtags'),
                    $this->modx->getOption('elements_caption_allowedattr')
                );
                $this->object->set('caption', $caption);
            }
        }

        if ($description = trim($this->getProperty('description', ''))) {
            $description = $isTV
                ? $this->modx->stripHtml(
                    $description,
                    $this->modx->getOption('elements_description_allowedtags'),
                    $this->modx->getOption('elements_description_allowedattr')
                )
                : strip_tags($description)
                ;
            $this->object->set('description', $description);
        }

        /* verify element has a name and that name does not already exist */

        $name = $this->getProperty($this->elementNameField, '');

        if (empty($name)) {
            $this->addFieldError($this->elementNameField, $this->modx->lexicon($this->objectType . '_err_ns_name'));
        } else {
            if ($this->alreadyExists($name)) {
                $this->addFieldError(
                    $this->elementNameField,
                    $this->modx->lexicon($this->objectType . '_err_ae', ['name' => $name])
                );
            }
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
        if ($this->object->staticContentChanged()) {
            if (!$this->object->isStaticSourceMutable()) {
                $this->addFieldError('static_file', $this->modx->lexicon('element_static_source_immutable'));
            } else if (!$this->object->isStaticSourceValidPath()) {
                $this->addFieldError('static_file', $this->modx->lexicon('element_static_source_protected_invalid'));
            }
        }

        return !$this->hasErrors();
    }

    public function alreadyExists($name)
    {
        return $this->modx->getCount($this->classKey, [
            'id:!=' => $this->object->get('id'),
            $this->elementNameField => $name,
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
        $fields = ['id', $this->elementNameField, 'description', 'locked', 'category', 'content'];
        return $this->success(
            '',
            array_merge(
                $this->object->get($fields),
                ['previous_category' => $this->previousCategory]
            )
        );
    }
}
