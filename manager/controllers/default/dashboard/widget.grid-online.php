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
        $timetocheck = (time()-(60*20));

        $c = $this->modx->newQuery('modManagerLog');
        $c->leftJoin('modUser','User');
        $c->where(array(
            'occurred:>' => strftime('%Y-%m-%d, %H:%M:%S',$timetocheck),
        ));
        $c->select($this->modx->getSelectColumns('modManagerLog','modManagerLog', '', array('occurred'), true) . ', MAX(`modManagerLog`.`occurred`) as `occurred`');
        $c->select($this->modx->getSelectColumns('modUser','User','',array('username')));
        $c->groupby('user');
        $c->sortby('occurred','DESC');
        $ausers = $this->modx->getIterator('modManagerLog',$c);

        $users = array();

        /** @var modActiveUser $user */
        $alt = false;
        foreach ($ausers as $user) {
            $userArray = $user->toArray();
            $userArray['currentAction'] = $user->get('action');
            $userArray['occurred'] = strftime('%b %d, %Y - %I:%M %p',strtotime($user->get('occurred'))+floatval($this->modx->getOption('server_offset_time',null,0)) * 3600);
            $userArray['class'] = $alt ? 'alt' : '';
            if (!$userArray['username']) {$userArray['username'] = 'Unknown';}
            $users[] = $this->getFileChunk('dashboard/onlineusers.row.tpl',$userArray);
        }

        $output = $this->getFileChunk('dashboard/onlineusers.tpl',array(
            'users' => implode("\n",$users),
            'curtime' => strftime('%I:%M %p',time()+floatval($this->modx->getOption('server_offset_time',null,0)) * 3600),
        ));
        return $output;
    }
}
return 'modDashboardWidgetWhoIsOnline';
