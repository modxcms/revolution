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
use MODX\Revolution\Processors\Model\UpdateProcessor;

/**
 * Updates a namespace from a grid
 * @param string $name A valid name
 * @param string $path An absolute path
 * @package MODX\Revolution\Processors\Workspace\PackageNamespace
 */
class Update extends UpdateProcessor
{
    public $classKey = modNamespace::class;
    public $languageTopics = ['workspace', 'namespace', 'lexicon'];
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
            $this->addFieldError('name', $this->modx->lexicon('namespace_err_ns_name'));
        }
        $this->object->set('name', $name);

        $this->object->set('path', trim($this->object->get('path')));
        $this->object->set('assets_path', trim($this->object->get('assets_path')));

        return parent::beforeSave();
    }
}
