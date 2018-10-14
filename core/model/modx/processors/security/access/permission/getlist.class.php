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
 * @package modx
 * @subpackage processors.security.permission
 */
class modAccessPermissionGetListProcessor extends modObjectGetListProcessor {
    public $checkListPermission = false;
    public $objectType = 'permission';
    public $classKey = 'modAccessPermission';
    public $permission = 'access_permissions';
    public $languageTopics = array('access','permission');

    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'query' => '',
        ));
        return $initialized;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('modAccessPolicyTemplate','Template');
        $c->query['DISTINCT'] = 'DISTINCT';
        $query = $this->getProperty('query','');
        if (!empty($query)) {
            $c->where(array(
                'modAccessPermission.name:LIKE' => '%'.$query.'%',
            ));
        }
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->select(array(
            'modAccessPermission.id',
            'modAccessPermission.name',
            'modAccessPermission.description',
            'Template.lexicon',
        ));
        $c->groupby('modAccessPermission.name');
        $name = $this->getProperty('name','');
        if (!empty($name)) {
            $c->where(array(
                $this->classKey . '.name:IN' => is_string($name) ? explode(',', $name) : $name
            ));
        }
        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->get(array('name','description'));

        $lexicon = $object->get('lexicon');
        if (!empty($lexicon)) {
            if (strpos($lexicon,':') !== false) {
                $this->modx->lexicon->load($lexicon);
            } else {
                $this->modx->lexicon->load('core:'.$lexicon);
            }
        }
        $objectArray['description'] = $this->modx->lexicon($objectArray['description']);
        return $objectArray;
    }
}
return 'modAccessPermissionGetListProcessor';
