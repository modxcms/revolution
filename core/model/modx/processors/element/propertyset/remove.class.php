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
 * Removes a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */

class modPropertySetRemoveProcessor extends modObjectRemoveProcessor {
    public $objectType = 'propertyset';
    public $classKey = 'modPropertySet';
    public $permission = 'delete_propertyset';
    public $languageTopics = array('propertyset');
}

return 'modPropertySetRemoveProcessor';
