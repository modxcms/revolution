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
 * A group of Resources which can be used for restricting access via ACLs and Permissions.
 *
 * @property string $name The name of the Resource Group
 * @property boolean $private_memgroup Deprecated
 * @property boolean $private_webgroup Deprecated
 *
 * @property modX|xPDO $xpdo
 * @see modResourceGroupResource
 * @see modAccessResourceGroup
 * @package modx
 */
class modResourceGroup extends modAccessibleSimpleObject {
    /**
     * Overrides xPDOObject::save to fire modX-specific events.
     *
     * {@inheritDoc}
     */
    public function save($cacheFlag= null) {
        $isNew = $this->isNew();

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnResourceGroupBeforeSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'resourceGroup' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }

        $saved = parent :: save($cacheFlag);

        /* invoke post-save events */
        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnResourceGroupSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'resourceGroup' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }
        return $saved;
    }

    /**
     * Overrides xPDOObject::remove to fire modX-specific events
     *
     * {@inheritDoc}
     */
     public function remove(array $ancestors= array ()) {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnBeforeResourceGroupRemove',array(
                'resourceGroup' => &$this,
                'ancestors' => $ancestors,
            ));
        }

        $removed = parent :: remove($ancestors);

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnResourceGroupRemove',array(
                'resourceGroup' => &$this,
                'ancestors' => $ancestors,
            ));
        }

        return $removed;
     }


    /**
     * Get all Resources within this Resource Group
     * @return array|null
     */
    public function getResources() {
        $c= $this->xpdo->newQuery('modResource');
        $c->innerJoin('modResourceGroupResource', 'ResourceGroupResources');
        $c->where(array ('ResourceGroupResources.document_group' => $this->get('id')));
        return $this->xpdo->getCollection('modResource', $c);
    }

    /**
     * Get all User Groups attached to this Resource Group
     * @return array
     */
    public function getUserGroups() {
        $access= $this->xpdo->getCollection('modAccessResourceGroup', array (
            'target' => $this->get('id'),
            'principal_class' => 'modUserGroup',
        ));
        $groups= array();
        /** @var modAccessResourceGroup $arg */
        foreach ($access as $arg) {
            $groups[$arg->get('target')]= $arg->getOne('Target');
        }
        return $groups;
    }

    /**
     * Check to see if the passed user (or current active user) has access to this Resource Group
     * @param null|modUser $user
     * @param string $context
     * @return boolean
     */
    public function hasAccess($user = null,$context = '') {
        /** @var modUser $user */
        $user = !empty($user) ? $user : $this->xpdo->user;
        if (is_object($context)) $context = $context->get('key');
        $resourceGroups = $user->getResourceGroups($context);
        $hasAccess = false;
        if (!empty($resourceGroups)) {
            foreach ($resourceGroups as $resourceGroup) {
                if (intval($resourceGroup) == $this->get('id')) {
                    $hasAccess = true;
                    break;
                }
            }
        }
        return $hasAccess;
    }
}
