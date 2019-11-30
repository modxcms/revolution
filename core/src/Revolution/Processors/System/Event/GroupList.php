<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Event;

use MODX\Revolution\modEvent;
use MODX\Revolution\Processors\Processor;
use PDO;

/**
 * Create a system setting
 * @package MODX\Revolution\Processors\System\Event
 */
class GroupList extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('events');
    }

    /**
     * @return mixed|string
     */
    public function process()
    {

        $list = [];

        $c = $this->modx->newQuery(modEvent::class);
        $c->distinct();
        $c->select(['groupname']);
        $c->sortby('groupname');

        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where([
                'groupname:LIKE' => '%' . $query . '%',
            ]);
        }

        $name = $this->getProperty('name', '');
        if (!empty($name)) {
            $c->where([
                'modEvent.groupname:IN' => is_string($name) ? explode(',', $name) : $name,
            ]);
        }

        if ($c->prepare() && $c->stmt->execute()) {
            foreach ($c->stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $list[] = [
                    'name' => $row['groupname'],
                ];
            }
        }

        $total = count($list);
        $start = $this->getProperty('start');
        $limit = $this->getProperty('limit');
        $list = array_slice($list, $start, $limit);

        return $this->outputArray($list, $total);
    }
}
