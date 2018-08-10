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
 * Provides a non-cacheable modScript implementation representing plugins.
 *
 * {@inheritdoc}
 *
 * @field boolean $cache_type Deprecated.
 * @field string $plugincode The code of the Plugin
 * @field boolean $locked Whether or not this Plugin is locked from editing except by Administrators
 * @field array $properties An array of default properties for the Plugin
 * @field boolean $disabled Whether or not this Plugin is active.
 * @field string $moduleguid Deprecated.
 *
 * @package modx
 * @extends modScript
 */
class modPlugin extends modScript {
    /**
     * Overrides xPDOObject::__construct to always set plugins as non-cacheable
     * @param xPDO $xpdo A reference to the xPDO|modX instance
     */
    function __construct(xPDO & $xpdo) {
        parent :: __construct($xpdo);
        $this->setCacheable(false);
    }

    /**
     * Overrides modElement::save to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag = null) {
        $isNew = $this->isNew();
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPluginBeforeSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'plugin' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }

        $saved = parent::save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPluginSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'plugin' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        } else if (!$saved && !empty($this->xpdo->lexicon)) {
            $msg = $isNew ? $this->xpdo->lexicon('plugin_err_create') : $this->xpdo->lexicon('plugin_err_save');
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$msg.$this->toArray());
        }

        return $saved;
    }

    /**
     * Overrides modElement::remove to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritdoc}
     */
    public function remove(array $ancestors= array ()) {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPluginBeforeRemove',array(
                'plugin' => &$this,
                'ancestors' => $ancestors,
            ));
        }

        $removed = parent :: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPluginRemove',array(
                'plugin' => &$this,
                'ancestors' => $ancestors,
            ));
        } else if (!$removed && !empty($this->xpdo->lexicon)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('plugin_err_remove').$this->toArray());
        }

        return $removed;
    }

    /**
     * Overrides modElement::getPropertySet to handle separate plugin event
     * property set calls.
     *
     * {@inheritdoc}
     */
    public function getPropertySet($setName = null) {
        if (empty($setName) && !empty($this->xpdo->event->propertySet)) {
            $setName = $this->xpdo->event->propertySet;
        }
        return parent :: getPropertySet($setName);
    }

    /**
     * Grabs a list of groups for the plugin.
     * @todo Implement this.
     *
     * @static
     * @param modResource $resource
     * @param array $sort
     * @param int $limit
     * @param int $offset
     * @return void
     */
    public static function listGroups(modResource &$resource, array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {

    }
}
