<?php
/**
 * @package modx
 */
/**
 * Represents a plugin registered for a specific event.
 *
 * @property int $pluginid The ID of the Plugin that this event is mapped to
 * @property string $event The name of this Plugin Event
 * @property int $priority The priority of this Event in the chain
 * @property int $propertyset The ID of the Property Set that may be attached to this Plugin Event
 * @see modPlugin
 * @package modx
 */
class modPluginEvent extends xPDOObject {
    /**
     * Overrides xPDOObject::save to fire modX-specific events.
     * 
     * {@inheritDoc}
     */
    public function save($cacheFlag = null) {
        $isNew = $this->isNew();
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPluginEventBeforeSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'pluginEvent' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }
        
        $saved = parent :: save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPluginEventSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'pluginEvent' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }
        return $saved;
    }

    /**
     * Overrides xPDOObject::remove to fire modX-specific events.
     *
     * {@inheritDoc}
     */
    public function remove(array $ancestors = array()) {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPluginEventBeforeRemove',array(
                'pluginEvent' => &$this,
                'ancestors' => $ancestors,
            ));
        }

        $removed = parent :: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnPluginEventRemove',array(
                'pluginEvent' => &$this,
                'ancestors' => $ancestors,
            ));
        }
        return $removed;
    }
}