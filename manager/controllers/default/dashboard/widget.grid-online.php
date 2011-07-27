<?php
/**
 * @package modx
 * @subpackage dashboard
 */
/**
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetWhoIsOnline extends modDashboardWidgetInterface {
    public function render() {
        $timetocheck = (time()-(60*20))+$this->modx->getOption('server_offset_time',null,0);
        $c = $this->modx->newQuery('modActiveUser');
        $c->where(array('lasthit:>' => $timetocheck));
        $c->sortby($this->modx->getSelectColumns('modActiveUser','modActiveUser','',array('username')),'ASC');
        $ausers = $this->modx->getCollection('modActiveUser',$c);
        $modx =& $this->modx;
        include_once $this->modx->getOption('processors_path'). 'system/actionlist.inc.php';

        $users = array();

        /** @var modActiveUser $user */
        foreach ($ausers as $user) {
            $currentaction = getAction($user->get('action'), $user->get('id'));
            $userArray = $user->toArray();
            $userArray['currentAction'] = $currentaction;
            $userArray['lastSeen'] = strftime('%X',$user->get('lasthit')+$serverOffset);
            $users[] = $this->getFileChunk('dashboard/onlineusers.row.tpl',$userArray);
        }
        
        $output = $this->getFileChunk('dashboard/onlineusers.tpl',array(
            'users' => implode("\n",$users),
            'curtime' => strftime('%H:%M %p',time()+$this->modx->getOption('server_offset_time',null,0)),
        ));
        return $output;
    }
}
return 'modDashboardWidgetWhoIsOnline';