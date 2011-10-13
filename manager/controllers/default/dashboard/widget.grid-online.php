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
        $c = $this->modx->newQuery('modManagerLog');
        $c->innerJoin('modUser','User');

        $c = $this->modx->newQuery('modManagerLog');
        $c->innerJoin('modUser','User');
        $c->where(array(
            'occurred:>' => strftime('%Y-%m-%d, %H:%M:%S',$timetocheck),
        ));
        $data['total'] = $this->modx->getCount('modManagerLog',$c);

        $c->select($this->modx->getSelectColumns('modManagerLog','modManagerLog'));
        $c->select($this->modx->getSelectColumns('modUser','User','',array('username')));
        $c->sortby('occurred','DESC');
        $c->groupby('user');
        $ausers = $this->modx->getIterator('modManagerLog',$c);

        $users = array();

        /** @var modActiveUser $user */
        $alt = false;
        foreach ($ausers as $user) {
            $userArray = $user->toArray();
            $userArray['currentAction'] = $user->get('action');
            $userArray['occurred'] = strftime('%b %d, %Y - %I:%M %p',strtotime($user->get('occurred')));
            $userArray['class'] = $alt ? 'alt' : '';
            $users[] = $this->getFileChunk('dashboard/onlineusers.row.tpl',$userArray);
        }
        
        $output = $this->getFileChunk('dashboard/onlineusers.tpl',array(
            'users' => implode("\n",$users),
            'curtime' => strftime('%I:%M %p',time()+$this->modx->getOption('server_offset_time',null,0)),
        ));
        return $output;
    }
}
return 'modDashboardWidgetWhoIsOnline';
