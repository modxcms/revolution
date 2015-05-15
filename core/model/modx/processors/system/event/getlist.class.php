<?php
/**
 * Gets a list of system events
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.event
 */
class modSystemEventGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('events');
    }
    public function getLanguageTopics() {
        return array('events');
    }
    public function initialize() {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 10,
            'sort' => 'groupname ASC, name',
            'dir' => 'ASC',
        ));
        return true;
    }
    
    public function process() {
        $data = $this->getData();
        
        $list = array();
        /** @var modEvent $event */
        foreach ($data['results'] as $event) {
            $eventArray = $event->toArray();

            $list[] = $eventArray;
        }
        return $this->outputArray($list,$data['total']);
    }

    /**
     * @return array
     */
    public function getData() {
        $limit = $this->getProperty('limit');
        $isLimit = !empty($limit);
        $data = array();

        $c = $this->modx->newQuery('modEvent');
		
		$query = $this->getProperty('query', '');
		if (!empty($query)) {
			$c->where(array(
				'name:LIKE' => '%' . $query . '%',
			));
		}
		
        $data['total'] = $this->modx->getCount('modEvent',$c);
        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        if ($isLimit) {
            $c->limit($limit,$this->getProperty('start'));
        }
        $data['results'] = $this->modx->getCollection('modEvent',$c);
        return $data;
    }
}
return 'modSystemEventGetListProcessor';