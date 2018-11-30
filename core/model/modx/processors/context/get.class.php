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
 * Grabs a context
 *
 * @param string $key The key of the context
 *
 * @package modx
 * @subpackage processors.context
 */
class modContextGetProcessor extends modObjectGetProcessor {
    public $classKey = 'modContext';
    public $languageTopics = array('context');
    public $permission = 'view_context';
    public $objectType = 'context';
    public $primaryKeyField = 'key';

    public function initialize() {
        $key = $this->getProperty('key');
        $this->setProperty('key',urldecode($key));
        return parent::initialize();
    }
}
return 'modContextGetProcessor';
