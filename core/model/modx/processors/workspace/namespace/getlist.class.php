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
 * Gets a list of namespaces
 *
 * @param string $name (optional) If set, will search by name
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.workspace.namespace
 */
class modNamespaceGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modNamespace';
    public $languageTopics = array('namespace','workspace');
    public $permission = 'namespaces';

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'search' => false,
        ));
        return $initialized;
    }

    /**
     * {@inheritDoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $search = $this->getProperty('search','');
        if (!empty($search)) {
            $c->where(array(
                'name:LIKE' => '%'.$search.'%',
                'OR:path:LIKE' => '%'.$search.'%',
            ));
        }
        return $c;
    }

    /**
     * Filter the query by the name property to get the right value in preselectFirstValue of MODx.combo.Namespace
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c) {
        $name = $this->getProperty('name','');
        if (!empty($name)) {
            $c->where(array(
                $this->classKey . '.name:IN' => is_string($name) ? explode(',', $name) : $name,
            ));
        }
        return $c;
    }

    /**
     * Prepare the Namespace for listing
     *
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $objectArray['perm'] = array();
        $objectArray['perm'][] = 'pedit';
        $objectArray['perm'][] = 'premove';
        return $objectArray;
    }
}
return 'modNamespaceGetListProcessor';
