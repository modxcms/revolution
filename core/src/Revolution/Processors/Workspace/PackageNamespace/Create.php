<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\PackageNamespace;

use MODX\Revolution\modNamespace;
use MODX\Revolution\Processors\Model\CreateProcessor;

/**
 * Creates a namespace
 * @param string $name The name of the namespace
 * @param string $path (optional) The path of the namespace
 * @package MODX\Revolution\Processors\Workspace\PackageNamespace
 */
class Create extends CreateProcessor
{
    public $classKey = modNamespace::class;
    public $languageTopics = ['namespace'];
    public $permission = 'namespaces';
    public $objectType = 'namespace';
    public $primaryKeyField = 'name';

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon($this->objectType . '_err_ns'));
        }
        $this->object->set('name', $name);

        $this->object->set('path', trim($this->object->get('path')));
        $this->object->set('assets_path', trim($this->object->get('assets_path')));
        
        return parent::beforeSave();
    }
}
