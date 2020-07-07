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
 * Gets a list of ACLs.
 *
 * @param string $type The type of ACL object
 * @param string $target (optional) The target of the ACL. Defauls to 0.
 * @param string $principal_class The class_key for the principal. Defaults to
 * modUserGroup.
 * @param string $principal (optional) The principal ID. Defaults to 0.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.access
 */

class modSecurityAccessGetListProcessor extends modObjectGetListProcessor {
    public $permission = 'access_permissions';
    public $languageTopics = array('access');
    public $defaultSortField = 'target';

    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 10,
            'target' => 0,
            'principal_class' => 'modUserGroup',
            'principal' => 0,
        ));

        $this->classKey = $this->getProperty('type');
        if (!$this->classKey) {
            return $this->modx->lexicon('access_type_err_ns');
        }

        return parent::initialize();
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey, ''));

        if ($this->getProperty('target')) {
            $c->where(array('target' => $this->getProperty('target')));
        }

        $c->where(array('principal_class' => $this->getProperty('principal_class')));

        if ($this->getProperty('principal')) {
            $c->where(array('principal' => $this->getProperty('principal')));
        }

        return $c;
    }

    public function getData() {
        $data = array();
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));

        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);
        $data['total'] = $this->modx->getCount($this->classKey,$c);

        $sort = $this->getProperty('sort');
        if ($sort) {
            $c->sortby($sort, $this->getProperty('dir'));
        }

        $sortArray = array(
            'target' => 'ASC',
            'principal_class' => 'DESC',
            'principal' => 'ASC',
            'authority' => 'ASC',
            'policy' => 'ASC',
        );
        foreach ($sortArray as $sortKey => $sortDir) {
            if ($sort != $sortKey) {
                $c->sortby($sortKey, $sortDir);
            }
        }

        if ($limit > 0) {
            $c->limit($limit,$start);
        }

        $data['results'] = $this->modx->getCollectionGraph($this->classKey, '{"Target":{},"Policy":{}}', $c);
        return $data;
    }

    public function prepareRow(xPDOObject $object) {
        $principal = $this->modx->getObject($object->get('principal_class'), $object->get('principal'));
        if (!$principal) {
            $principal = $this->modx->newObject($object->get('principal_class'), array('name' => $this->getAnonymName()));
        }

        $policyName = !empty($object->Policy) ? $object->Policy->get('name') : $this->modx->lexicon('no_policy_option');

        if ($object->Target) {
            $targetName = ($this->classKey == 'modAccessContext') ? $object->Target->get('key') : $object->Target->get('name');
        } else {
            $targetName = $this->getAnonymName();
        }

        $objArray = array(
            'id' => $object->get('id'),
            'target' => $object->get('target'),
            'target_name' => $targetName,
            'principal_class' => $object->get('principal_class'),
            'principal' => $object->get('principal'),
            'principal_name' => $principal->get('name'),
            'authority' => $object->get('authority'),
            'policy' => $object->get('policy'),
            'policy_name' => $policyName,
        );

        if (isset($object->_fieldMeta['context_key'])) {
            $objArray['context_key'] = $object->get('context_key');
        }

        // Prevent default Admin ACL from edit and remove
        $objArray['cls'] = (($object->get('target') == 'mgr') && ($principal->get('name') == 'Administrator') &&
            ($policyName == 'Administrator') && ($object->get('authority') == 0)) ? '' : 'pedit premove';

        return $objArray;
    }
    
    /**
     * Return formatted and translated string for anonymous value.
     * @return string
     */
    protected function getAnonymName()
    {
        return '(' . $this->modx->lexicon('anonymous') . ')';
    }
}

return 'modSecurityAccessGetListProcessor';
