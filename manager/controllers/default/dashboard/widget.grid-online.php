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
        $c->innerJoin('modUser','User');
        $c->where(array(
            'occurred:>' => strftime('%Y-%m-%d, %H:%M:%S',$timetocheck),
        ));
        $c->where(
            "occurred = (SELECT MAX(`occurred`)
                    FROM {$this->modx->getTableName('modManagerLog')} AS log2
                    WHERE `log2`.`user` = `modManagerLog`.`user`
                    GROUP BY `user`)"
        );
        $data['total'] = $this->modx->getCount('modManagerLog',$c);

        $c->select($this->modx->getSelectColumns('modManagerLog','modManagerLog'));
        $c->select($this->modx->getSelectColumns('modUser','User','',array('username')));
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
