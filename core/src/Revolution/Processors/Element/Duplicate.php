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

use MODX\Revolution\Processors\Model\DuplicateProcessor;

/**
 * Abstract class for Duplicate Element processors. To be extended for each derivative element type.
 *
 * @abstract
 *
 * @package MODX\Revolution\Processors\Element
 */
class Duplicate extends DuplicateProcessor
{
    public $hasStaticFile = false;

    public function initialize()
    {
        // Intitializing parent first, as we need the Element object created before moving forward
        if (parent::initialize() === true) {
            return $this->newObject->setupElement($this);
        }
    }

    /**
     * Validate the form
     *
     * @return boolean
     */
    public function beforeSave()
    {
        $name = $this->getProperty($this->nameField);

        // verify element with that name does not already exist
        if ($this->alreadyExists($name)) {
            $this->addFieldError($this->nameField, $this->modx->lexicon($this->objectType . '_err_ae', [
                'name' => $name,
            ]));
        }

        if ($this->hasStaticFile) {
            $newFile = $this->getProperty('static_file');
            if (!empty($newFile)) {
                $this->newObject->set('static_file', $newFile);
            }
            $this->newObject->staticFileAbsolutePath = $this->newObject->getStaticFileAbsolutePath();

            // Check writability of file and file path (also checks for allowable file extension)
            $fileValidated = $this->newObject->validateStaticFile($this);

            if ($fileValidated === true) {
                $this->newObject->staticIsWritable = true;
            }
        }
        return !$this->hasErrors();
    }

    public function cleanup()
    {
        $fields = $this->newObject->get(['id', 'name', 'description', 'category', 'locked']);
        $fields['redirect'] = (bool)$this->getProperty('redirect', false);

        return $this->success('', $fields);
    }

    public function afterSave()
    {
        if ($this->getProperty('clearCache')) {
            $this->modx->cacheManager->refresh();
        }

        return parent::afterSave();
    }
}
