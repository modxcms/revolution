<?php

namespace MODX\Processors\System\Event;

use MODX\Processors\modProcessor;

/**
 * Create a system setting
 *
 * @package modx
 * @subpackage processors.system.events
 */
class GroupList extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('events');
    }


    public function process()
    {

        $list = [];

        $c = $this->modx->newQuery('modEvent');
        $c->distinct();
        $c->select(['groupname']);
        $c->sortby('groupname', 'ASC');

        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where([
                'groupname:LIKE' => '%' . $query . '%',
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
