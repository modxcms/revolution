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

    protected $hasStaticFile = false;

    public function initialize()
    {
        // Intitializing parent first, as we need the Element object created before moving forward
        if (parent::initialize()) {
            $className = array_pop(explode('\\', $this->classKey));
            /*
                There is at least one other Element type (modPropertySet) where static files
                do not apply, so here we determine whether static processing will be needed
                based on the Element being created.
            */
            $hasStaticContentOption = in_array(
                $className,
                ['modChunk', 'modPlugin', 'modSnippet', 'modTemplate', 'modTemplateVar']
            );
            if ($hasStaticContentOption && intval($this->getProperty('static', 0)) === 1) {
                $file = $this->getProperty('static_file');
                if (!empty($file)) {
                    $this->hasStaticFile = true;
                    $mediaSourceId = (int)$this->getProperty('source');
                    // When file media source is set to "None"
                    if ($mediaSourceId === 0) {
                        $this->object->ignoreMediaSource = true;
                        if (strpos($file, '/') === 0) {
                            $this->object->staticPathIsAbsolute = true;
                        }
                    }
                    // When there is an assigned media source
                    if ($mediaSourceId > 0) {
                        $this->object->ignoreMediaSource = false;
                        $this->setProperty('static_file', ltrim($file, DIRECTORY_SEPARATOR));
                    }

                    $this->object->staticElementMediaSourceId = $mediaSourceId;
                    $this->object->isStaticElementFile = true;

                    if ($this->object->getSource()) {
                        // Stop if error fetching media source
                        if ($this->object->_source->hasErrors()) {
                            $this->addFieldError('static_file', reset($this->object->_source->getErrors()));
                            return false;
                        }
                    }
                    $this->object->relayStaticPropertiesToMediaSource([
                        'isStaticElementFile',
                        'ignoreMediaSource',
                        'staticPathIsAbsolute'
                    ]);
                }
            }
            return true;
        }
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

        /* make sure a name was specified */
        $nameField = $this->classKey === modTemplate::class ? 'templatename' : 'name';
        $name = $this->getProperty($nameField, '');
        if (empty($name)) {
            $this->addFieldError($nameField, $this->modx->lexicon($this->objectType . '_err_ns_name'));
        } else {
            if ($this->alreadyExists($name)) {
                /* if changing name, but new one already exists */
                $this->modx->error->addField(
                    $nameField,
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
        if ($this->hasStaticFile) {
            // For new elements, only need to continue static processing if content is present
            if ($this->object->get('content') !== '') {
                $this->object->staticFileAbsolutePath = $this->object->getSourceFile();

                // Check writability of file and file path (also checks for allowable file extension)
                $fileValidated = $this->object->validateStaticFile();
                if ($fileValidated !== true) {
                    if (array_key_exists('msgData', $fileValidated)) {
                        $this->addFieldError('static_file', $this->modx->lexicon($fileValidated['msgLexKey'], $fileValidated['msgData']));
                    } else {
                        $this->addFieldError('static_file', $this->modx->lexicon($fileValidated['msgLexKey']));
                    }
                    return false;
                } else {
                    $this->object->staticIsWritable = true;
                }
            }
        }
        return !$this->hasErrors();
    }

    public function alreadyExists($name)
    {
        $nameField = $this->classKey === modTemplate::class ? 'templatename' : 'name';

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
        return $this->success(
            '',
            array_merge(
                $this->object->get(['id', 'name', 'description', 'locked', 'category', 'content']),
                ['previous_category' => $this->previousCategory]
            )
        );
    }
}
