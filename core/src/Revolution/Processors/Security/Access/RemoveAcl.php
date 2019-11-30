<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access;

use MODX\Revolution\Processors\Model\RemoveProcessor;

/**
 * Remove an ACL.
 * @param string $type The class_key for the ACL.
 * @param string $id The ID of the ACL.
 * @package MODX\Revolution\Processors\Security\Access
 */
class RemoveAcl extends RemoveProcessor
{
    public $objectType = 'access';
    public $permission = 'access_permissions';
    public $languageTopics = ['access'];

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        $this->classKey = $this->getProperty('type');
        $id = $this->getProperty('id');
        if (!$this->classKey || !$id) {
            return $this->modx->lexicon($this->objectType . '_type_err_ns');
        }
        $this->object = $this->modx->getObject($this->classKey, $id);
        if (!$this->object) {
            return $this->modx->lexicon($this->objectType . '_err_nf');
        }

        return true;
    }

    /**
     * Reload current user's ACLs
     * @return bool
     */
    public function afterRemove()
    {
        if ($this->modx->getUser()) {
            $this->modx->user->getAttributes([], '', true);
        }

        return true;
    }
}
