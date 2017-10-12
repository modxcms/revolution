<?php
/**
 * Gets a list of all users who are online
 *
 * @package modx
 * @subpackage processors.security.user
 */

class modUserWhoIsOnlineProcessor extends modObjectGetListProcessor {
  public $classKey = 'modManagerLog';
  public $defaultSortField = 'occurred';

  public function prepareQueryBeforeCount(xPDOQuery $query) {

    $date_timezone = !empty($this->modx->getOption('date_timezone')) ? $this->modx->getOption('date_timezone') : date_default_timezone_get();
    $datetime = new DateTime($date_timezone);
    $interval = new DateInterval("PT20M");
    $interval->invert = 1;
    $datetime->add($interval);
    $timetocheck = $datetime->format('Y-m-d H:i:s');

    $query->where(array('occurred:>' => $timetocheck));
    $query->sortby('occurred','DESC');
    $query->groupby('user');
    $query->select($this->modx->getSelectColumns('modManagerLog','modManagerLog'));
    $query->select($this->modx->getSelectColumns('modUser','User','',array('username')));
    $query->innerJoin('modUser','User');

    return $query;
  }
  public function process() {
    $beforeQuery = $this->beforeQuery();
    if ($beforeQuery !== true) {
      return $this->failure($beforeQuery);
    }
    $data = $this->getData();
    $list = $this->iterate($data);

    if ($list) {
      $dateformat = $this->modx->getOption('manager_date_format') . " " . $this->modx->getOption('manager_time_format');
      $namecheck = $this->modx->getOption('manager_use_fullname');

      foreach($list as $row) {
        $datetime = new DateTime($row['occurred']);
        $row['occurred'] = $datetime->format($dateformat);

        if ($namecheck == 1) {
          $profile = $this->modx->getObject('modUserProfile', array('internalKey' => $row['user']));
          $row['username'] = $profile->get('fullname');
        }

        $list[] = $row;
      }
    }

    return $this->outputArray($list,$data['total']);
  }
}

return 'modUserWhoIsOnlineProcessor';