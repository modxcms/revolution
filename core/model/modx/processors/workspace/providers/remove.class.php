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
 * Remove a provider
 *
 * @param integer $id The provider ID
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
class modProviderRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'transport.modTransportProvider';
    public $languageTopics = array('workspace');
    public $permission = 'providers';
    public $objectType = 'provider';
}
return 'modProviderRemoveProcessor';
