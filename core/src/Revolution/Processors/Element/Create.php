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
use MODX\Revolution\Processors\Model\CreateProcessor;
use MODX\Revolution\modTemplate;
use MODX\Revolution\Validation\modValidator;

/**
 * Abstract class for Create Element processors. To be extended for each derivative element type.
 *
 * @abstract
 *
 * @package MODX\Revolution\Processors\Element
 */
abstract class Create extends CreateProcessor
{
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

    /**
     * Cleanup the process and send back the response
     *
     * @return array
     */
    public function cleanup()
    {
        $this->clearCache();
        $fields = ['id', 'description', 'locked', 'category'];
        array_push($fields, ($this->classKey == modTemplate::class ? 'templatename' : 'name'));

        return $this->success('', $this->object->get($fields));
    }

    /**
     * Validate the form
     *
     * @return boolean
     */
    public function beforeSave()
    {
        $name = $this->getProperty('name');

        /* verify element with that name does not already exist */
        if ($this->alreadyExists($name)) {
            $this->addFieldError('name', $this->modx->lexicon($this->objectType . '_err_ae', [
                'name' => $name,
            ]));
        }

        $category = $this->getProperty('category', 0);
        if (!empty($category)) {
            /** @var modCategory $category */
            $category = $this->modx->getObject(modCategory::class, ['id' => $category]);
            if ($category === null) {
                $this->addFieldError('category', $this->modx->lexicon('category_err_nf'));
            }
            if ($category !== null && !$category->checkPolicy('add_children')) {
                $this->addFieldError('category', $this->modx->lexicon('access_denied'));
            }
        }

        $locked = (bool)$this->getProperty('locked', false);
        $this->object->set('locked', $locked);

        $this->setElementProperties();
        $this->validateElement();

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
        // end has static file
        return !$this->hasErrors();
    }

    /**
     * Check to see if a Chunk already exists with specified name
     *
     * @param string $name
     *
     * @return bool
     */
    public function alreadyExists($name)
    {
        if ($this->classKey == modTemplate::class) {
            $c = ['templatename' => $name];
        } else {
            $c = ['name' => $name];
        }

        return $this->modx->getCount($this->classKey, $c) > 0;
    }

    /**
     * Set the properties on the Element
     *
     * @return mixed
     */
    public function setElementProperties()
    {
        $propertyData = $this->getProperty('propdata', null);
        if ($propertyData != null && is_string($propertyData)) {
            $propertyData = $this->modx->fromJSON($propertyData);
        }
        if (is_array($propertyData)) {
            $this->object->setProperties($propertyData);
        }

        return $propertyData;
    }

    /**
     * Run object-level validation on the element
     *
     * @return void
     */
    public function validateElement()
    {
        if (!$this->object->validate()) {
            /** @var modValidator $validator */
            $validator = $this->object->getValidator();
            if ($validator->hasMessages()) {
                foreach ($validator->getMessages() as $message) {
                    $this->addFieldError($message['field'], $this->modx->lexicon($message['message']));
                }
            }
        }
    }

    /**
     * Clear the cache post-save
     *
     * @return void
     */
    public function clearCache()
    {
        if ($this->getProperty('clearCache')) {
            $this->modx->cacheManager->refresh();
        }
    }
}
