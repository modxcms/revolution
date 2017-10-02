<?php
/**
 * Gets a list of manager log actions
 *
 * @param string $actionType (optional) If set, will filter by action type
 * @param integer $user (optional) If set, will filter by user
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to occurred.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 *
 * @package modx
 * @subpackage processors.system.log
 */
class modSystemLogGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('logs');
    }
    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 20,
            'start' => 0,
            'sort' => 'occurred',
            'dir' => 'DESC',
            'user' => false,
            'actionType' => false,
            'dateStart' => false,
            'dateEnd' => false,
            'dateFormat' => $this->modx->getOption('manager_date_format') .', '. $this->modx->getOption('manager_time_format'),
        ));
        return true;
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process() {
        $data = $this->getData();

        $list = array();
        /** @var modManagerLog $log */
        foreach ($data['results'] as $log) {
            $logArray = $this->prepareLog($log);
            if (!empty($logArray)) {
                $list[] = $logArray;
            }
        }

        return $this->outputArray($list,$data['total']);
    }

    /**
     * Get a collection of modManagerLog objects
     *
     * @return array
     */
    public function getData() {
        $actionType = $this->getProperty('actionType');
        $classKey = $this->getProperty('classKey');
        $item = $this->getProperty('item');
        $user = $this->getProperty('user');
        $dateStart = $this->getProperty('dateStart');
        $dateEnd = $this->getProperty('dateEnd');
        $limit = $this->getProperty('limit');
        $isLimit = !empty($limit);
        $data = array();

        /* check filters */
        $wa = array();
        if (!empty($actionType)) { $wa['action:LIKE'] = '%'.$actionType.'%'; }
        if (!empty($classKey)) { $wa['classKey:LIKE'] = '%'.$classKey.'%'; }
        if (!empty($item)) { $wa['item:LIKE'] = '%'.$item.'%'; }
        if (!empty($user)) { $wa['user'] = $user; }
        if (!empty($dateStart)) {
            $dateStart = strftime('%Y-%m-%d',strtotime($dateStart.' 00:00:00'));
            $wa['occurred:>='] = $dateStart;
        }
        if (!empty($dateEnd)) {
            $dateEnd = strftime('%Y-%m-%d',strtotime($dateEnd.' 23:59:59'));
            $wa['occurred:<='] = $dateEnd;
        }

        /* build query */
        $c = $this->modx->newQuery('modManagerLog');
        $c->innerJoin('modUser','User');
        if (!empty($wa)) $c->where($wa);
        $data['total'] = $this->modx->getCount('modManagerLog',$c);

        $c->select($this->modx->getSelectColumns('modManagerLog','modManagerLog'));
        $c->select($this->modx->getSelectColumns('modUser','User','',array('username')));
        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        $c->sortby('occurred','DESC');
        if ($isLimit) $c->limit($limit,$this->getProperty('start'));
        $data['results'] = $this->modx->getIterator('modManagerLog',$c);
        return $data;
    }

    /**
     * Prepare a log entry for listing
     *
     * @param modManagerLog $log
     * @return array
     */
    public function prepareLog(modManagerLog $log) {
        $logArray = $log->toArray();
        if (strpos($logArray['action'], '.') !== false) {
            // Action is prefixed with a namespace, assume we need to load a package
            $exp = explode('.', $logArray['action']);
            $ns = $exp[0];
            $path = $this->modx->getOption(
                "{$ns}.core_path",
                null,
                $this->modx->getOption('core_path') . "components/{$ns}/"
            ) . 'model/';
            $this->modx->addPackage($ns, $path);
        }
        if (!empty($logArray['classKey']) && !empty($logArray['item'])) {
            $logArray['name'] = $logArray['classKey'] . ' (' . $logArray['item'] . ')';
            /** @var xPDOObject $obj */
            $obj = $this->modx->getObject($logArray['classKey'], $logArray['item']);
            if ($obj && ($obj->get($obj->getPK()) == $logArray['item'])) {
                $nameField = $this->getNameField($logArray['classKey']);
                $k = $obj->getField($nameField, true);
                if (!empty($k)) {
                    $pk = $obj->get('id');
                    $logArray['name'] = $obj->get($nameField).(!empty($pk) ? ' ('.$pk.')' : '');
                }
            }
        } else {
            $logArray['name'] = $log->get('item');
        }
        $logArray['occurred'] = date($this->getProperty('dateFormat'), strtotime($logArray['occurred']));
        return $logArray;
    }

    /**
     * Get the name field of the class
     *
     * @param string $classKey
     * @return string
     */
    public function getNameField($classKey) {
        $field = 'name';
        switch ($classKey) {
            case 'modResource':
            case 'modWeblink':
            case 'modSymlink':
            case 'modStaticResource':
            case 'modDocument':
                $field = 'pagetitle';
                break;
            case 'modAction': $field = 'controller'; break;
            case 'modCategory': $field = 'category'; break;
            case 'modContext': $field = 'key'; break;
            case 'modTemplate': $field = 'templatename'; break;
            case 'modUser': $field = 'username'; break;
            case 'modMenu': $field = 'text'; break;
            case 'modSystemSetting':
            case 'modContextSetting':
            case 'modUserSetting':
                $field = 'key';
                break;
            default: break;
        }
        return $field;
    }
}
return 'modSystemLogGetListProcessor';
