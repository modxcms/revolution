<?php
/**
 * Loads the system info page
 *
 * @package modx
 * @subpackage controllers.system
 */
if (!$modx->hasPermission('view_sysinfo')) return $modx->error->failure($modx->lexicon('access_denied'));

$serverOffset = $modx->getOption('server_offset_time',null,0) * 60 * 60;

/* general */
$modx->smarty->assign('version',$modx->version['full_appname']);
$modx->smarty->assign('code_name',$modx->version['code_name']);
$modx->smarty->assign('servertime',strftime('%I:%M:%S %p', time()));
$modx->smarty->assign('localtime',strftime('%I:%M:%S %p', time()+$serverOffset));
$modx->smarty->assign('serveroffset',$serverOffset / (60*60));

/* database info */
$modx->smarty->assign('database_type',$modx->getOption('dbtype'));
/* TODO: Make database-agnostic version call
/* will need modification for other database types
 */
$stmt= $modx->query("SELECT VERSION()");
if ($stmt) {
    $result= $stmt->fetch(PDO::FETCH_COLUMN);
    $stmt->closeCursor();
} else {
    $result='-';
}
$modx->smarty->assign('database_version',$result);
$modx->smarty->assign('database_charset',$modx->getOption('charset'));
$modx->smarty->assign('database_name',str_replace('`','',$modx->getOption('dbname')));
$modx->smarty->assign('database_server',$modx->getOption('host'));

/* active users */
$timetocheck = strftime('%m-%d-%Y %H:%M:%S',time()-(60*20));
$c = $modx->newQuery('modManagerLog');
$c->select('
    `modManagerLog`.*,
    `User`.`username` AS `username`
');
$c->innerJoin('modUser','User');
$c->where(array('
    occurred:>' => $timetocheck,
));
$c->groupby('modManagerLog.user');
$c->sortby('occurred','ASC');
$ausers = $modx->getCollection('modManagerLog',$c);

foreach ($ausers as $user) {
    $offset = strtotime($user->get('occurred')) + $serverOffset;
    $user->set('lasthit',strftime('%b %d, %Y %I:%M %p',$offset));
}
$modx->smarty->assign('ausers',$ausers);
$modx->smarty->assign('now',strftime('%b %d, %Y %I:%M %p',time()));


/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.grid.databasetables.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.grid.resource.active.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/system/info.js');


return $modx->smarty->fetch('system/info.tpl');