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
 * Remove a Resource Group ACL for a usergroup
 *
 * @param integer $id The ID of the ACL
 *
 * @package modx
 * @subpackage processors.security.access.resourcegroup
 */
class modUserGroupAccessResourceGroupRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modAccessResourceGroup';
    public $objectType = 'access_rgroup';
    public $languageTopics = array('access');
    public $permission = 'access_permissions';
}

return 'modUserGroupAccessResourceGroupRemoveProcessor';
