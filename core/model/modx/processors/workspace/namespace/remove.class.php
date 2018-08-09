<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Removes a namespace.
 *
 * @param string $name The name of the namespace.
 *
 * @package modx
 * @subpackage processors.workspace.namespace
 */
class modNamespaceRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modNamespace';
    public $languageTopics = array('namespace','workspace','lexicon');
    public $permission = 'namespaces';
    public $objectType = 'namespace';
    public $primaryKeyField = 'name';

    public function beforeRemove() {
        return 'core' != $this->getProperty('name');
    }
}
return 'modNamespaceRemoveProcessor';
