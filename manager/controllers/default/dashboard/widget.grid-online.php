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
        $c->setClassAlias('lastlog');
        $c->innerJoin('modManagerLog', 'modManagerLog', array('`lastlog`.`id` = `modManagerLog`.`id`'));
        $c->leftJoin('modUser', 'User');
            $tmp = $this->modx->newQuery('modManagerLog');
            $tmp->setClassAlias('tmp');
            $tmp->select(array('MAX(`id`) AS `id`', '`user`'));
            $tmp->where(array('tmp.occurred:>' => strftime('%Y-%m-%d, %H:%M:%S', $timetocheck)));
            $tmp->groupby('user');
            $tmp->prepare();
            $c->query['from']['tables'][0]['table'] = '('.$tmp->toSQL().')';
        $c->select($this->modx->getSelectColumns('modManagerLog', 'modManagerLog'));
        $c->select($this->modx->getSelectColumns('modUser', 'User', '', array('username')));
        $c->sortby('occurred', 'DESC');
        $ausers = $this->modx->getIterator('modManagerLog', $c);

        $users = array();

        /** @var modActiveUser $user */
        $alt = false;
        foreach ($ausers as $user) {
            $userArray = $user->toArray();
            $userArray['currentAction'] = $user->get('action');
            $userArray['occurred'] = date(
                    $this->modx->getOption('manager_date_format') .' - '. $this->modx->getOption('manager_time_format'),
                    strtotime($user->get('occurred')) + floatval($this->modx->getOption('server_offset_time', null, 0)) * 3600
            );
            $userArray['class'] = $alt ? 'alt' : '';
            if (!$userArray['username']) {
                $userArray['username'] = 'anonymous';
            }
            $users[] = $this->getFileChunk('dashboard/onlineusers.row.tpl', $userArray);
        }

        $output = $this->getFileChunk('dashboard/onlineusers.tpl', array(
            'users' => implode("\n", $users),
            'curtime' => strftime(
                '%I:%M %p',
                time() + floatval($this->modx->getOption('server_offset_time', null, 0)) * 3600
            ),
        ));
        return $output;
    }
}
return 'modDashboardWidgetWhoIsOnline';
