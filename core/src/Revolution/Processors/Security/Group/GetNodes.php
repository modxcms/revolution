<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Group;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modUserGroup;

/**
 * Get the user groups in tree node format
 * @param string $id The parent ID
 * @package MODX\Revolution\Processors\Security\Group
 */
class GetNodes extends Processor
{
    /** @var string $id */
    public $id;

    /** @var modUserGroup $userGroup */
    public $userGroup;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('usergroup_view');
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['user'];
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function initialize()
    {
        $this->setDefaultProperties([
            'id' => 0,
            'sort' => 'name',
            'dir' => 'ASC',
            'showAnonymous' => true,
        ]);

        return true;
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $this->id = $this->parseId($this->getProperty('id'));

        $groups = $this->getGroups();

        $list = [];
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
    protected function parseId($id)
    {
        return str_replace('n_ug_', '', $id);
    }

    /**
     * Get the User Groups within the filter
     * @return array
     */
    public function getGroups()
    {
        $data = [];
        $c = $this->modx->newQuery(modUserGroup::class);
        $c->where(['parent' => $this->id]);
        $data['total'] = $this->modx->getCount(modUserGroup::class, $c);
        $c->sortby($this->getProperty('sort'), $this->getProperty('dir'));
        $data['results'] = $this->modx->getCollection(modUserGroup::class, $c);

        return $data;
    }

    /**
     * Add the Anonymous group to the list
     * @param array $list
     * @return array
     */
    public function addAnonymous(array $list)
    {
        if ($this->getProperty('showAnonymous') && empty($this->id)) {
            $cls = 'pupdate';
            $list[] = [
                'text' => '(' . $this->modx->lexicon('anonymous') . ')',
                'id' => 'n_ug_0',
                'expanded' => true,
                'allowDrop' => false,
                'leaf' => true,
                'type' => 'usergroup',
                'cls' => $cls,
                'iconCls' => 'icon icon-group',
            ];
        }

        return $list;
    }

    /**
     * Prepare a User Group for listing
     * @param modUserGroup $group
     * @return array
     */
    public function prepareGroup(modUserGroup $group)
    {
        $cls = 'padduser pcreate pupdate';
        if ($group->get('id') != 1) {
            $cls .= ' premove';
        }
        $c = $this->modx->newQuery(modUserGroup::class);
        $c->where(['parent' => $group->get('id')]);
        $c->limit(1);
        $count = $this->modx->getCount(modUserGroup::class, $c);
        $itemArray = [
            'text' => htmlentities($group->get('name'), ENT_QUOTES, 'UTF-8') . ' (' . $group->get('id') . ')',
            'id' => 'n_ug_' . $group->get('id'),
            'hasChildren' => $count > 0,
            'type' => 'usergroup',
            'qtip' => $group->get('description'),
            'cls' => $cls,
            'allowDrop' => true,
            'iconCls' => 'icon icon-group',
        ];

        if (!$itemArray['hasChildren']) {
            $itemArray['expanded'] = true;
        }

        return $itemArray;
    }
}
