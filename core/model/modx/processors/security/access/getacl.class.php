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
 * Gets an ACL.
 *
 * @param string $type The class_key for the ACL.
 * @param string $id The ID of the ACL.
 *
 * @package modx
 * @subpackage processors.security.access
 */

class modSecurityAccessGetAclProcessor extends modObjectGetProcessor {
    public $objectType = 'access';
    public $permission = 'access_permissions';
    public $languageTopics = array('access');

    public function initialize() {
        $this->classKey = $this->getProperty('type');
        $id = $this->getProperty('id');
        if (!$this->classKey || !$id) {
            return $this->modx->lexicon($this->objectType.'_type_err_ns');
        }
        $this->object = $this->modx->getObject($this->classKey, $id);
        if (!$this->object) {
            return $this->modx->lexicon($this->objectType.'_err_nf');
        }
        return true;
    }
}

return 'modSecurityAccessGetAclProcessor';
