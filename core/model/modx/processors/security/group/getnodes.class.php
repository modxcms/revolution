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
 * Get the user groups in tree node format
 *
 * @param string $id The parent ID
 *
 * @package modx
 * @subpackage processors.security.group
 */
class modSecurityGroupGetNodesProcessor extends modProcessor {
    /** @var string $id */
    public $id;
    /** @var modUserGroup $userGroup */
    public $userGroup;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('usergroup_view');
    }
    /**
     * {@inheritDoc}
     * @return array
     */
    public function getLanguageTopics() {
        return array('user');
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'id' => 0,
            'sort' => 'name',
            'dir' => 'ASC',
            'showAnonymous' => true,
        ));
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function process() {
        $this->id = $this->parseId($this->getProperty('id'));

        $groups = $this->getGroups();

        $list = array();
        $list = $this->addAnonymous($list);

        /** @var modUserGroup $group */
        foreach ($groups['results'] as $group) {
            $groupArray = $this->prepareGroup($group);
            if (!empty($groupArray)) {
                $list[] = $groupArray;
            }
        }

        return $this->toJSON($list);
    }

    /**
     * Parse the ID to get the parent group
     * @param string $id
     * @return mixed
     */
    protected function parseId($id) {
        return str_replace('n_ug_','',$id);
    }

    /**
     * Get the User Groups within the filter
     * @return array
     */
    public function getGroups() {
        $data = array();
        $c = $this->modx->newQuery('modUserGroup');
        $c->where(array(
            'parent' => $this->id,
        ));
        $data['total'] = $this->modx->getCount('modUserGroup',$c);
        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        $data['results'] = $this->modx->getCollection('modUserGroup',$c);
        return $data;
    }

    /**
     * Add the Anonymous group to the list
     *
     * @param array $list
     * @return array
     */
    public function addAnonymous(array $list) {
        if ($this->getProperty('showAnonymous') && empty($this->id)) {
            $cls = 'pupdate';
            $list[] = array(
                'text' => '('.$this->modx->lexicon('anonymous').')',
                'id' => 'n_ug_0',
                'leaf' => true,
                'type' => 'usergroup',
                'cls' => $cls,
                'iconCls' => 'icon icon-group',
            );
        }
        return $list;
    }

    /**
     * Prepare a User Group for listing
     *
     * @param modUserGroup $group
     * @return array
     */
    public function prepareGroup(modUserGroup $group) {
        $cls = 'padduser pcreate pupdate';
        if ($group->get('id') != 1) {
            $cls .= ' premove';
        }
        $c = $this->modx->newQuery('modUserGroup');
        $c->where(array(
            'parent' => $group->get('id'),
        ));
        $c->limit(1);
        $count = $this->modx->getCount('modUserGroup', $c);
        return array(
            'text' => htmlentities($group->get('name'), ENT_QUOTES, 'UTF-8') . ' ('.$group->get('id') . ')',
            'id' => 'n_ug_'.$group->get('id'),
            'leaf' => ($count > 0 ? false : true),
            'type' => 'usergroup',
            'qtip' => $group->get('description'),
            'cls' => $cls,
            'iconCls' => 'icon icon-group',
        );
    }

}
return 'modSecurityGroupGetNodesProcessor';
