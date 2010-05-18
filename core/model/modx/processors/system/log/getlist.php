<?php
/**
 * Gets a list of manager log actions
 *
 * @param string $action_type (optional) If set, will filter by action type
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
if (!$modx->hasPermission('logs')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'occurred');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');

$user = $modx->getOption('user',$scriptProperties,false);
$actionType = $modx->getOption('actionType',$scriptProperties,false);
$dateStart = $modx->getOption('dateStart',$scriptProperties,false);
$dateEnd = $modx->getOption('dateEnd',$scriptProperties,false);

/* check filters */
$wa = array();
if (!empty($actionType)) { $wa['action:LIKE'] = '%'.$actionType.'%'; }
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
$c = $modx->newQuery('modManagerLog');
$c->innerJoin('modUser','User');
if (!empty($wa)) $c->where($wa);
$count = $modx->getCount('modManagerLog',$c);

$c->select('
    `modManagerLog`.*,
    `User`.`username` AS `username`
');
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$logs = $modx->getCollection('modManagerLog',$c);

$list = array();
foreach ($logs as $log) {
    $logArray = $log->toArray();
    if (!empty($logArray['classKey']) && !empty($logArray['item'])) {
        $obj = $modx->getObject($logArray['classKey'],$logArray['item']);
        if ($obj) {
            $nameField = getNameField($logArray['classKey']);
            $pk = $obj->get('id');
            $logArray['name'] = $obj->get($nameField).(!empty($pk) ? ' ('.$pk.')' : '');
        } else {
            $logArray['name'] = $logArray['classKey'] . ' (' . $logArray['item'] . ')';
        }
    } else {
        $logArray['name'] = $log->get('item');
    }
    $logArray['occurred'] = strftime('%a %b %d, %Y %H:%I:%S %p',strtotime($logArray['occurred']));
    $list[] = $logArray;
}

return $this->outputArray($list,$count);

function getNameField($classKey) {
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
        default: break;
    }
    return $field;
}