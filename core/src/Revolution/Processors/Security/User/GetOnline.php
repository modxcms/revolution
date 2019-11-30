<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\User;

use DateInterval;
use DateTime;
use MODX\Revolution\modManagerLog;
use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use PDO;
use xPDO\Om\xPDOQuery;
use xPDO\Om\xPDOObject;

/**
 * Gets a list of all users who are online
 * @package MODX\Revolution\Processors\Security\User
 */
class GetOnline extends GetListProcessor
{
    public $classKey = modManagerLog::class;
    public $defaultSortField = 'occurred';
    public $defaultSortDirection = 'desc';

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     * @throws \Exception
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $date_timezone = !empty($this->modx->getOption('date_timezone')) ? $this->modx->getOption('date_timezone') : date_default_timezone_get();
        $datetime = new DateTime($date_timezone);
        $interval = new DateInterval('PT20M');
        $interval->invert = 1;
        $datetime->add($interval);
        $timeToCheck = $datetime->format('Y-m-d H:i:s');

        $q = $this->modx->newQuery($this->classKey, ['occurred:>' => $timeToCheck]);
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

        $c->select($this->modx->getSelectColumns(modManagerLog::class, 'modManagerLog'));

        return $c;
    }

    /**
     * Prepare the row for iteration
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $row = $object->toArray();

        /** @var modUser $user */
        if ($user = $object->getOne('User')) {
            $row = array_merge($row,
                $user->get(['username']),
                $user->Profile->get(['fullname', 'email']),
                ['photo' => $user->getPhoto(64, 64)]
            );
            /** @var modUserGroup $group */
            $row['group'] = ($group = $user->getOne('PrimaryGroup')) ? $group->get('name') : '';
        }

        return $row;
    }
}
