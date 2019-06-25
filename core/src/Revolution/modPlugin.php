<?php

namespace MODX\Revolution;

use xPDO\xPDO;

/**
 * Provides a non-cacheable modScript implementation representing plugins.
 *
 * {@inheritdoc}
 *
 * @property boolean                 $cache_type Deprecated.
 * @property string                  $plugincode The code of the Plugin
 * @property boolean                 $locked     Whether or not this Plugin is locked from editing except by Administrators
 * @property array                   $properties An array of default properties for the Plugin
 * @property boolean                 $disabled   Whether or not this Plugin is active.
 * @property string                  $moduleguid Deprecated.
 *
 * @property modElementPropertySet[] $PropertySets
 * @property modPluginEvent[]        $PluginEvents
 *
 * @package MODX\Revolution
 */
class modPlugin extends modScript
{
    /**
     * Overrides xPDOObject::__construct to always set plugins as non-cacheable
     *
     * @param xPDO $xpdo A reference to the xPDO|modX instance
     */
    function __construct(xPDO & $xpdo)
    {
        parent:: __construct($xpdo);
        $this->setCacheable(false);
    }

    /**
     * Overrides modElement::save to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag = null)
    {
        $isNew = $this->isNew();
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPluginBeforeSave', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'plugin' => &$this,
                'cacheFlag' => $cacheFlag,
            ]);
        }

        $saved = parent::save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPluginSave', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'plugin' => &$this,
                'cacheFlag' => $cacheFlag,
            ]);
        } else {
            if (!$saved && !empty($this->xpdo->lexicon)) {
                $msg = $isNew ? $this->xpdo->lexicon('plugin_err_create') : $this->xpdo->lexicon('plugin_err_save');
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $msg . $this->toArray());
            }
        }

        return $saved;
    }

    /**
     * Overrides modElement::remove to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritdoc}
     */
    public function remove(array $ancestors = [])
    {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPluginBeforeRemove', [
                'plugin' => &$this,
                'ancestors' => $ancestors,
            ]);
        }

        $removed = parent:: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPluginRemove', [
                'plugin' => &$this,
                'ancestors' => $ancestors,
            ]);
        } else {
            if (!$removed && !empty($this->xpdo->lexicon)) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $this->xpdo->lexicon('plugin_err_remove') . $this->toArray());
            }
        }

        return $removed;
    }

    /**
     * Overrides modElement::getPropertySet to handle separate plugin event
     * property set calls.
     *
     * {@inheritdoc}
     */
    public function getPropertySet($setName = null)
    {
        if (empty($setName) && !empty($this->xpdo->event->propertySet)) {
            $setName = $this->xpdo->event->propertySet;
        }

        return parent:: getPropertySet($setName);
    }

    /**
     * Grabs a list of groups for the plugin.
     *
     * @todo Implement this.
     *
     * @static
     *
     * @param modResource $resource
     * @param array       $sort
     * @param int         $limit
     * @param int         $offset
     *
     * @return void
     */
    public static function listGroups(modResource &$resource, array $sort = ['id' => 'ASC'], $limit = 0, $offset = 0)
    {

    }
}
