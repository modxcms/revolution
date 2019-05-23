<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOObject;

/**
 * Represents a plugin registered for a specific event.
 *
 * @property int    $pluginid    The ID of the Plugin that this event is mapped to
 * @property string $event       The name of this Plugin Event
 * @property int    $priority    The priority of this Event in the chain
 * @property int    $propertyset The ID of the Property Set that may be attached to this Plugin Event
 *
 * @package MODX\Revolution
 */
class modPluginEvent extends xPDOObject
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
            $this->xpdo->invokeEvent('OnPluginEventBeforeSave', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'pluginEvent' => &$this,
                'cacheFlag' => $cacheFlag,
            ]);
        }

        $saved = parent:: save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPluginEventSave', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'pluginEvent' => &$this,
                'cacheFlag' => $cacheFlag,
            ]);
        }

        return $saved;
    }

    /**
     * Overrides xPDOObject::remove to fire modX-specific events.
     *
     * {@inheritDoc}
     */
    public function remove(array $ancestors = [])
    {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPluginEventBeforeRemove', [
                'pluginEvent' => &$this,
                'ancestors' => $ancestors,
            ]);
        }

        $removed = parent:: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPluginEventRemove', [
                'pluginEvent' => &$this,
                'ancestors' => $ancestors,
            ]);
        }

        return $removed;
    }
}
