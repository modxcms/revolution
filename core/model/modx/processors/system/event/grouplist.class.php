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
 * Create a system setting
 *
 * @package modx
 * @subpackage processors.system.events
 */
class modSystemEventsGroupListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('events');
    }

    public function process() {

		$list = array();

		$c = $this->modx->newQuery('modEvent');
		$c->distinct();
		$c->select(array('groupname'));
		$c->sortby('groupname', 'ASC');

		$query = $this->getProperty('query');
		if (!empty($query)) {
			$c->where(array(
				'groupname:LIKE' => '%' . $query . '%',
			));
		}
        $name = $this->getProperty('name','');
        if (!empty($name)) {
            $c->where(array(
                'modEvent.groupname:IN' => is_string($name) ? explode(',', $name) : $name
            ));
        }

		if ($c->prepare() && $c->stmt->execute()) {
			foreach ($c->stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
				$list[] = array(
					'name' => $row['groupname'],
				);
			}
		}

		$total = count($list);
		$start = $this->getProperty('start');
		$limit = $this->getProperty('limit');
		$list = array_slice($list, $start, $limit);

		return $this->outputArray($list, $total);
	}
}

return 'modSystemEventsGroupListProcessor';
