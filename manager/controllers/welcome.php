<?php
/**
 * Loads the welcome page
 *
 * @package modx
 * @subpackage manager
 */
if (!$modx->hasPermission('home')) return $modx->error->failure($modx->lexicon('permission_denied'));

$modx->lexicon->load('configcheck');
$modx->smarty->assign('site_name',$modx->config['site_name']);

/* assign current time message */
$modx->smarty->assign('online_users_msg',$modx->lexicon('onlineusers_message',array(
    'curtime' => strftime('%X', time()+$modx->config['server_offset_time'])
)));

/* do some config checks */
include_once MODX_PROCESSORS_PATH . 'system/config_check.inc.php';
if ($config_check_results != $modx->lexicon('configcheck_ok')) {
	$modx->smarty->assign('config_display',true);
	$modx->smarty->assign('config_check_results',$config_check_results);
} else {
	$modx->smarty->assign('config_display',false);
}

/* user info : TODO: convert to revo */
if (isset($_SESSION['mgrLastlogin']) && !empty($_SESSION['mgrLastLogin'])) {
    $previous_login = strftime('%c', $_SESSION['mgrLastlogin']+$modx->config['server_offset_time']);
} else {
    $previous_login = $modx->lexicon('not_set');
}
$modx->smarty->assign('previous_login',$previous_login);

/* online users
 * :TODO: convert this to revo/modext */

$timetocheck = (time()-(60*20))+$modx->config['server_offset_time'];
$c = $modx->newQuery('modActiveUser');
$c->where(array('lasthit:>' => $timetocheck));
$c->sortby('username','ASC');
$ausers = $modx->getCollection('modActiveUser',$c);
include_once MODX_PROCESSORS_PATH . 'system/actionlist.inc.php';
foreach ($ausers as $user) {
	$currentaction = getAction($user->get('action'), $user->get('id'));
	$user->set('currentaction',$currentaction);
	$user->set('lastseen',strftime('%X',$user->lasthit+$modx->config['server_offset_time']));
}
$modx->smarty->assign('ausers',$ausers);


/* grab rss feeds */
$modx->loadClass('xmlrss.modRSSParser','',false,true);
$rssparser = new modRSSParser($modx);

$url = $modx->config['feed_modx_news'];
$rss = $rssparser->parse($url);
foreach (array_keys($rss->items) as $key) {
	$item= &$rss->items[$key];
    $item['pubdate'] = strftime('%c',$item['date_timestamp']);
}
$modx->smarty->assign('newsfeed',$rss->items);

$url = $modx->config['feed_modx_security'];
$rss = $rssparser->parse($url);
foreach (array_keys($rss->items) as $key) {
	$item= &$rss->items[$key];
    $item['pubdate'] = strftime('%c',$item['date_timestamp']);
}
$modx->smarty->assign('securefeed',$rss->items);

/* load JS scripts for page */
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/widgets/modx.panel.welcome.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/widgets/security/modx.grid.user.recent.resource.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/sections/welcome.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-welcome"
        ,site_name: "'.htmlentities($modx->config['site_name']).'"
        ,config_display: '.($config_display ? 'true': 'false').'
        ,user: "'.$modx->user->get('id').'"
    });
});
// ]]>
</script>');


return $modx->smarty->fetch('welcome.tpl');