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
use MODX\Revolution\modX;

/**
 * Sort users and user groups, effectively repositioning users into proper groups
 * @param string $data The encoded in JSON tree data
 * @package MODX\Revolution\Processors\Security\Group
 */
class Sort extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('usergroup_save');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['user'];
    }

    /**
     * @return array|mixed|string
     * @throws \xPDO\xPDOException
     */
    public function process()
    {
        $data = $this->getProperty('data');
        if (empty($data)) {
            return $this->failure($this->modx->lexicon('invalid_data'));
        }
        $data = urldecode($data);
        $data = $this->modx->fromJSON($data);
        if (empty($data)) {
            return $this->failure($this->modx->lexicon('invalid_data'));
        }

        $this->sortGroups($data);

        return $this->success();
    }

    /**
     * Sort and rearrange any groups in the data
     * @param array $data
     * @return void
     */
    public function sortGroups(array $data)
    {
        $groups = [];
        $this->getGroupsFormatted($groups, $data);

        /* readjust groups */
        foreach ($groups as $groupArray) {
            if (empty($groupArray['id'])) {
                continue;
            }

            if ($groupArray['id'] === 1) {
                continue;
            }

            /** @var modUserGroup $userGroup */
            $userGroup = $this->modx->getObject(modUserGroup::class, $groupArray['id']);
            if ($userGroup === null) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not sort group ' . $groupArray['id'] . ' because it could not be found.');
                continue;
            }
            $oldParentId = $userGroup->get('parent');


            if ($groupArray['parent'] === $userGroup->get('id')) {
                continue;
            }

            if ($oldParentId !== $groupArray['parent']) {
                if ($groupArray['parent'] !== 0) {
                    /** @var modUserGroup $parentUserGroup */
                    $parentUserGroup = $this->modx->getObject(modUserGroup::class, $groupArray['parent']);
                    if ($parentUserGroup === null) {
                        continue;
                    }
                }

                $userGroup->set('parent', $groupArray['parent']);
            }

            $userGroup->save();
        }
    }

    /**
     * @param $ar_nodes
     * @param $cur_level
     * @param int $parent
     */
    protected function getGroupsFormatted(&$ar_nodes, $cur_level, $parent = 0)
    {
        $order = 0;
        foreach ($cur_level as $id => $children) {
            $id = substr($id, 2); /* get rid of CSS id n_ prefix */
            if (substr($id, 0, 2) === 'ug') {
                $ar_nodes[] = [
                    'id' => intval(substr($id, 3)),
                    'parent' => intval(substr($parent, 3)),
                    'order' => $order,
                ];
                $order++;
            }
            $this->getGroupsFormatted($ar_nodes, $children, $id);
        }
    }
}
