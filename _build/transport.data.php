<?php
// This script builds the transport data files from the modx instance you instantiate.

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tstart= $mtime;

// get rid of time limit
set_time_limit(0);

// override with your own defines here (see build.config.sample.php)
@ require_once dirname(__FILE__) . '/build.config.php';

if (!defined('MODX_CORE_PATH'))
    define('MODX_CORE_PATH', dirname(dirname(__FILE__)) . '/core/');
if (!defined('MODX_CONFIG_KEY'))
    define('MODX_CONFIG_KEY', 'config');
require_once (MODX_CORE_PATH . 'model/modx/modx.class.php');
$modx= new modX();
$modx->initialize('mgr');

$cacheManager= $modx->getCacheManager();
$modx->setLogLevel(xPDO::LOG_LEVEL_ERROR);
$modx->setLogTarget('ECHO');


// Get all Actions
$content= "<?php\n";
$query= $modx->newQuery('modAction');
$query->where(array('namespace' => 'core'));
$query->sortby('id');

$collection= $modx->getCollection('modAction', $query);
foreach ($collection as $key => $c) {
    $content.= $cacheManager->generateObject($c, "collection['{$key}']", false, false, 'xpdo');
}
$cacheManager->writeFile(dirname(__FILE__) . '/data/transport.core.actions.php', $content);
unset($content, $collection, $key, $c);


// Get all Menus
$content= "<?php\n";
$query= $modx->newQuery('modMenu');
$query->sortby('id');
$collection= $modx->getCollection('modMenu', $query);
foreach ($collection as $key => $c) {
    $content.= $cacheManager->generateObject($c, "collection['{$key}']", false, false, 'xpdo');
}
$cacheManager->writeFile(dirname(__FILE__) . '/data/transport.core.menus.php', $content);
unset($content, $collection, $key, $c);


// Get all Events
$content= "<?php\n";
$query= $modx->newQuery('modEvent');
$query->sortby('id');
$collection= $modx->getCollection('modEvent', $query);
foreach ($collection as $key => $c) {
    $content.= $cacheManager->generateObject($c, "collection['{$key}']", false, false, 'xpdo');
}
$cacheManager->writeFile(dirname(__FILE__) . '/data/transport.core.events.php', $content);
unset($content, $collection, $key, $c);


// Get all Content Types
$content= "<?php\n";
$query= $modx->newQuery('modContentType');
$query->sortby('id');
$collection= $modx->getCollection('modContentType', $query);
foreach ($collection as $key => $c) {
    $content.= $cacheManager->generateObject($c, "collection['{$key}']", false, false, 'xpdo');
}
$cacheManager->writeFile(dirname(__FILE__) . '/data/transport.core.content_types.php', $content);
unset($content, $collection, $key, $c);

// Get all System Settings
$content= "<?php\n";
$query= $modx->newQuery('modSystemSetting');
$query->select($modx->getSelectColumns('modSystemSetting', '', '', array('editedon'), true));
$query->where(array('namespace' => 'core'));
$query->sortby($modx->escape('key'));
$collection= $modx->getCollection('modSystemSetting', $query);
foreach ($collection as $key => $c) {
    $content.= $cacheManager->generateObject($c, "collection['{$key}']", false, false, 'xpdo');
}
$cacheManager->writeFile(dirname(__FILE__) . '/data/transport.core.system_settings.php', $content);
unset($content, $collection, $key, $c);


// Get all Context Settings
$content= "<?php\n";
$query= $modx->newQuery('modContextSetting');
$query->select($modx->getSelectColumns('modContextSetting', '', '', array('editedon'), true));
$query->where(array('namespace' => 'core'));
$query->sortby($modx->escape('context_key'));
$query->sortby($modx->escape('key'));
$collection= $modx->getCollection('modContextSetting', $query);
foreach ($collection as $key => $c) {
    $content.= $cacheManager->generateObject($c, "collection['{$key}']", false, false, 'xpdo');
}
$cacheManager->writeFile(dirname(__FILE__) . '/data/transport.core.context_settings.php', $content);
unset($content, $collection, $key, $c);


// Get the Admin Group
$content= "<?php\n";
$collection= $modx->getCollection('modUserGroup', array('id' => 1));
foreach ($collection as $key => $c) {
    $content.= $cacheManager->generateObject($c, "collection['{$key}']", false, false, 'xpdo');
}
$cacheManager->writeFile(dirname(__FILE__) . '/data/transport.core.usergroups.php', $content);
unset($content, $collection, $key, $c);


// Get the default UserGroupRoles
$content= "<?php\n";
$collection= $modx->getCollection('modUserGroupRole');
foreach ($collection as $key => $c) {
    $content.= $cacheManager->generateObject($c, "collection['{$key}']", false, false, 'xpdo');
}
$cacheManager->writeFile(dirname(__FILE__) . '/data/transport.core.usergrouproles.php', $content);
unset($content, $collection, $key, $c);


// Get the default Access Policies
$content= "<?php\n";
$collection= $modx->getCollection('modAccessPolicy');
foreach ($collection as $key => $c) {
    $content.= $cacheManager->generateObject($c, "collection['{$key}']", false, false, 'xpdo');
}
$cacheManager->writeFile(dirname(__FILE__) . '/data/transport.core.accesspolicies.php', $content);
unset($content, $collection, $key, $c);


// Get the default AccessContext ACLs
$content= "<?php\n";
$collection= $modx->getCollection('modAccessContext');
foreach ($collection as $key => $c) {
    $content.= $cacheManager->generateObject($c, "collection['{$key}']", false, false, 'xpdo');
}
$cacheManager->writeFile(dirname(__FILE__) . '/data/transport.core.access_contexts.php', $content);
unset($content, $collection, $key, $c);


$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

echo "\nExecution time: {$totalTime}\n";

exit ();
