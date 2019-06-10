<?php

namespace MODX\Revolution;

/**
 * A group of Resources which can be used for restricting access via ACLs and Permissions.
 *
 * @property string                        $name             The name of the Resource Group
 * @property boolean                       $private_memgroup Deprecated
 * @property boolean                       $private_webgroup Deprecated
 *
 * @property modResourceGroupResource[]    $ResourceGroupResources
 * @property modTemplateVarResourceGroup[] $TemplateVarResourceGroups
 * @property modAccessResourceGroup[]      $Acls
 *
 * @package MODX\Revolution
 */
class modResourceGroup extends modAccessibleSimpleObject
{
    /**
     * Overrides xPDOObject::save to fire modX-specific events.
     *
     * {@inheritDoc}
     */
    public function save($cacheFlag = null)
    {
        $isNew = $this->isNew();

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnResourceGroupBeforeSave', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'resourceGroup' => &$this,
                'cacheFlag' => $cacheFlag,
            ]);
        }

        $saved = parent:: save($cacheFlag);

        /* invoke post-save events */
        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnResourceGroupSave', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'resourceGroup' => &$this,
                'cacheFlag' => $cacheFlag,
            ]);
        }

        return $saved;
    }

    /**
     * Overrides xPDOObject::remove to fire modX-specific events
     *
     * {@inheritDoc}
     */
    public function remove(array $ancestors = [])
    {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnBeforeResourceGroupRemove', [
                'resourceGroup' => &$this,
                'ancestors' => $ancestors,
            ]);
        }

        $removed = parent:: remove($ancestors);

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnResourceGroupRemove', [
                'resourceGroup' => &$this,
                'ancestors' => $ancestors,
            ]);
        }

        return $removed;
    }


    /**
     * Get all Resources within this Resource Group
     *
     * @return array|null
     */
    public function getResources()
    {
        $c = $this->xpdo->newQuery(modResource::class);
        $c->innerJoin(modResourceGroupResource::class, 'ResourceGroupResources');
        $c->where(['ResourceGroupResources.document_group' => $this->get('id')]);

        return $this->xpdo->getCollection(modResource::class, $c);
    }

    /**
     * Get all User Groups attached to this Resource Group
     *
     * @return array
     */
    public function getUserGroups()
    {
        $access = $this->xpdo->getCollection(modAccessResourceGroup::class, [
            'target' => $this->get('id'),
            'principal_class' => modUserGroup::class,
        ]);
        $groups = [];
        /** @var modAccessResourceGroup $arg */
        foreach ($access as $arg) {
            $groups[$arg->get('target')] = $arg->getOne('Target');
        }

        return $groups;
    }

    /**
     * Check to see if the passed user (or current active user) has access to this Resource Group
     *
     * @param null|modUser $user
     * @param string       $context
     *
     * @return boolean
     */
    public function hasAccess($user = null, $context = '')
    {
        /** @var modUser $user */
        $user = !empty($user) ? $user : $this->xpdo->user;
        if (is_object($context)) {
            /** @var modContext $context */
            $context = $context->get('key');
        }
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
