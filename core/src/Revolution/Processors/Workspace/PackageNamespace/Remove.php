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
use MODX\Revolution\Processors\Model\RemoveProcessor;

/**
 * Removes a namespace.
 * @param string $name The name of the namespace.
 * @package MODX\Revolution\Processors\Workspace\PackageNamespace
 */
class Remove extends RemoveProcessor
{
    public $classKey = modNamespace::class;
    public $languageTopics = ['namespace', 'workspace', 'lexicon'];
    public $permission = 'namespaces';
    public $objectType = 'namespace';
    public $primaryKeyField = 'name';

    /**
     * @return bool
     */
    public function beforeRemove()
    {
        return 'core' !== $this->getProperty('name');
    }
}
