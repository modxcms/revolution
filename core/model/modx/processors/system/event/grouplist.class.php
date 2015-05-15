<?php
/**
 * Create a system setting
 *
 * @package modx
 * @subpackage processors.system.events
 */
class modSystemEventsGroupListProcessor extends modProcessor {
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
		
		if ($c->prepare() && $c->stmt->execute()) {
			foreach ($c->stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
				$list[] = array(
					'name' => $row['groupname'],
				);
			}
		}
		
		return $this->outputArray($list, count($list));
	}
}

return 'modSystemEventsGroupListProcessor';