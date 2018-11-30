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
 * Remove a Media Source ACL for a usergroup
 *
 * @param integer $id The ID of the ACL
 *
 * @package modx
 * @subpackage processors.security.access.source
 */
class modSecurityAccessUserGroupSourceRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'sources.modAccessMediaSource';
    public $languageTopics = array('source','access','user');
    public $permission = 'access_permissions';
    public $objectType = 'access_source';
}
return 'modSecurityAccessUserGroupSourceRemoveProcessor';
