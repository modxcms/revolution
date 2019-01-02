<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use xPDO\Om\xPDOQuery;
use xPDO\Om\xPDOObject;

/**
 * Gets a list of all users who are online
 *
 * @package modx
 * @subpackage processors.security.user
 */
class modUserWhoIsOnlineProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modManagerLog';
    public $defaultSortField = 'occurred';
    public $defaultSortDirection = 'desc';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     * @throws Exception
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {

        $date_timezone = !empty($this->modx->getOption('date_timezone'))
            ? $this->modx->getOption('date_timezone')
            : date_default_timezone_get();
        $datetime = new DateTime($date_timezone);
        $interval = new DateInterval("PT20M");
        $interval->invert = 1;
        $datetime->add($interval);
        $timetocheck = $datetime->format('Y-m-d H:i:s');

        $q = $this->modx->newQuery($this->classKey, ['occurred:>' => $timetocheck]);
        $q->select('MAX(id), user');
        $q->groupby('user');
        $q->limit($this->getProperty('limit', 10));
        if ($q->prepare() && $q->stmt->execute()) {
            if ($ids = $q->stmt->fetchAll(PDO::FETCH_COLUMN)) {
                $c->where(['id:IN' => $ids]);
            } else {
                $c->where(['id' => -1]);
            }
        }

        $c->select($this->modx->getSelectColumns('modManagerLog', 'modManagerLog'));

        return $c;
    }

    /**
     * Prepare the row for iteration
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $row = $object->toArray();

        /** @var modUser $user */
        if ($user = $object->getOne('User')) {
            $row = array_merge($row,
                $user->get(['username']),
                $user->Profile->get(['fullname', 'email', 'photo']),
                ['gravatar' => $user->getGravatar(64)]
            );
            /** @var modUserGroup $group */
            $row['group'] = ($group = $user->getOne('PrimaryGroup'))
                ? $group->get('name')
                : '';
        }

        return $row;
    }
}

return 'modUserWhoIsOnlineProcessor';
