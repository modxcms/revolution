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
 * Gets a list of plugins associated to system event
 *
 * @package modx
 * @subpackage processors.element.plugin.event
 */

class modPluginEventGetAssocProcessor extends modObjectGetListProcessor {
    public $classKey = 'modPlugin';
    public $languageTopics = array('plugin');
    public $permission = 'view_plugin';
    public $objectType = 'plugin';

    /**
     * Filter by system event
     *
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->innerJoin('modPluginEvent','modPluginEvent',array(
            'modPluginEvent.pluginid = modPlugin.id',
            'modPluginEvent.event' => $this->getProperty('event'),
        ));

        return $c;
    }

    /**
     * Add selection of priority and propertyset
     *
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->select($this->modx->getSelectColumns('modPlugin','modPlugin'));
        $c->select(array(
            'modPluginEvent.priority',
            'modPluginEvent.propertyset',
        ));
        return $c;
    }

    /**
     * Filter only desired fields
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $objectArray = array(
            $objectArray['id'],
            $objectArray['name'],
            $objectArray['priority'],
            $objectArray['propertyset'],
        );
        return $objectArray;
    }
}

return 'modPluginEventGetAssocProcessor';
