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
 * Remove Event from a Plugin
 * @param int $plugin Plugin primary key
 * @param int $event Event primary key
 * @package modx
 * @subpackage processors.element.plugin.event
 */

class modPluginEventRemoveProcessor extends modObjectRemoveProcessor {
    public $objectType = 'plugin_event';
    public $classKey = 'modPluginEvent';
    public $permission = 'delete_plugin';
    public $languageTopics = array('plugin');

    public function initialize() {
        $plugin = $this->getProperty('plugin', 0);
        $event = $this->getProperty('event', 0);
        if (!$plugin || !$event) {
            return $this->modx->lexicon($this->objectType.'_err_ns');
        }

        $this->object = $this->modx->getObject($this->classKey, array(
            'pluginid' => $plugin,
            'event' => $event,
        ));

        if (!$this->object) {
            return $this->modx->lexicon($this->objectType.'_err_nf');
        }

        return true;
    }

}

return 'modPluginEventRemoveProcessor';
