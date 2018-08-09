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
 * Gets a list of all users who are online
 *
 * @package modx
 * @subpackage processors.security.user
 */

class modUserWhoIsOnlineProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modManagerLog';
    public $defaultSortField = 'occurred';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     * @throws Exception
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $date_timezone = $this->modx->getOption('date_timezone')
            ? $this->modx->getOption('date_timezone')
            : date_default_timezone_get();
        $datetime = new DateTime($date_timezone);
        $interval = new DateInterval("PT20M");
        $interval->invert = 1;
        $datetime->add($interval);
        $timetocheck = $datetime->format('Y-m-d H:i:s');

        $q = $this->modx->newQuery($this->classKey, array('occurred:>' => $timetocheck));
        $q->select('MAX(id), user');
        $q->groupby('user');
        if ($q->prepare() && $q->stmt->execute()) {
            if ($ids = $q->stmt->fetchAll(PDO::FETCH_COLUMN)) {
                $c->where(array('id:IN' => $ids));
            } else {
                $c->where(array('id' => -1));
            }
        }

        $c->sortby('occurred', 'desc');
        $c->innerJoin('modUser', 'User');
        $c->select($this->modx->getSelectColumns('modManagerLog', 'modManagerLog'));
        $c->select($this->modx->getSelectColumns('modUser', 'User', '', array('username')));

        return $c;
    }


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $beforeQuery = $this->beforeQuery();
        if ($beforeQuery !== true) {
            return $this->failure($beforeQuery);
        }
        $data = $this->getData();
        $list = $this->iterate($data);

        if ($list) {
            $dateformat = $this->modx->getOption('manager_date_format') . " " . $this->modx->getOption('manager_time_format');
            $namecheck = $this->modx->getOption('manager_use_fullname');

            foreach ($list as $idx => $row) {
                $datetime = new DateTime($row['occurred']);
                $row['occurred'] = $datetime->format($dateformat);

                if ($namecheck == 1) {
                    $profile = $this->modx->getObject('modUserProfile', array('internalKey' => $row['user']));
                    $row['username'] = $profile->get('fullname');
                }

                $list[$idx] = $row;
            }
        }

        return $this->outputArray($list, $data['total']);
    }
}

return 'modUserWhoIsOnlineProcessor';
