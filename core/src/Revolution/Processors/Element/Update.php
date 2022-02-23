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

    public $hasStaticFile = false;

    public function initialize()
    {
        // Intitializing parent first, as we need the Element object created before moving forward
        if (parent::initialize()) {
            if (!$this->hasErrors()) {
                $ready = $this->object->setupElement($this);
            }
            // $this->modx->log(
            //     \modX::LOG_LEVEL_ERROR,
            //     "\r\tUpdate->initialize
            //     \$testProp2: " . $this->testProp2
            // );
            return $ready;
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
            // if ($this->object->get('content') !== '') {
            // $this->modx->log(
            //     \modX::LOG_LEVEL_ERROR,
            //     "\r\tUpdate->beforeSave: about to get source file and set absolute path"
            // );
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
            // }
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
