<?php
/**
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


    public function getResources() {
        $c= $this->xpdo->newQuery('modResource');
        $c->innerJoin('modResourceGroupResource', 'ResourceGroupResources');
        $c->where(array ('ResourceGroupResources.document_group' => $this->get('id')));
        return $this->xpdo->getCollection('modResource', $c);
    }

    public function getUserGroups() {
        $access= $this->xpdo->getCollection('modAccessResourceGroup', array (
            'target' => $this->get('id'),
            'principal_class' => 'modUserGroup',
        ));
        $groups= array();
        foreach ($access as $arg) {
            $groups[$arg->get('membergroup')]= $arg->getOne('Target');
        }
        return $groups;
    }
}