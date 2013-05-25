<?php
/**
 * Adds all top Menu items to build
 *
 * @var xPDO $xpdo
 * 
 * @package modx
 * @subpackage build
 */
$menus = array();

/* ***************** CONTENT MENU ***************** */
$menus[0]= $xpdo->newObject('modMenu');
$menus[0]->fromArray(array (
  'text' => 'content',
  'parent' => '',
  'action' => '',
  'description' => '',
  'icon' => '',
  'menuindex' => 0,
  'permissions' => 'menu_site',
), '', true, true);

$children = array();

/* new document resource */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'new_resource',
  'parent' => 'content',
  'action' => 'resource/create',
  'description' => 'new_resource_desc',
  'icon' => '',
  'menuindex' => 0,
  'permissions' => 'new_document',
), '', true, true);

/* preview */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'preview',
  'parent' => 'dashboard',
  'action' => '',
  'description' => 'preview_desc',
  'icon' => '',
  'menuindex' => 1,
  'handler' => 'MODx.preview(); return false;',
), '', true, true);

/* new static resource 
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'text' => 'new_static_resource',
  'parent' => 'content',
  'action' => 'resource/create',
  'description' => 'new_static_resource_desc',
  'icon' => '',
  'menuindex' => 2,
  'params' => '&class_key=modStaticResource',
  'permissions' => 'new_static_resource',
), '', true, true);

/* new weblink resource 
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array (
  'text' => 'new_weblink',
  'parent' => 'content',
  'action' => 'resource/create',
  'description' => 'new_weblink_desc',
  'icon' => '',
  'menuindex' => 3,
  'params' => '&class_key=modWebLink',
  'permissions' => 'new_weblink',
), '', true, true);

/* new symlink resource 
$children[4]= $xpdo->newObject('modMenu');
$children[4]->fromArray(array (
  'text' => 'new_symlink',
  'parent' => 'content',
  'action' => 'resource/create',
  'description' => 'new_symlink_desc',
  'icon' => '',
  'menuindex' => 4,
  'params' => '&class_key=modSymLink',
  'permissions' => 'new_symlink',
), '', true, true);

/* import html */
$children[5]= $xpdo->newObject('modMenu');
$children[5]->fromArray(array (
  'text' => 'import_site',
  'parent' => 'content',
  'action' => 'system/import/html',
  'description' => 'import_site_desc',
  'icon' => 'images/icons/application_side_contract.png',
  'menuindex' => 5,
  'permissions' => 'import_static',
), '', true, true);

/* import resources */
$children[6]= $xpdo->newObject('modMenu');
$children[6]->fromArray(array (
  'text' => 'import_resources',
  'parent' => 'content',
  'action' => 'system/import',
  'description' => 'import_resources_desc',
  'icon' => 'images/icons/application_side_contract.png',
  'menuindex' => 6,
  'permissions' => 'import_static',
), '', true, true);

/* content types */
$children[7]= $xpdo->newObject('modMenu');
$children[7]->fromArray(array (
  'text' => 'content_types',
  'parent' => 'content',
  'action' => 'system/contenttype',
  'description' => 'content_types_desc',
  'icon' => 'images/icons/elements2.png',
  'menuindex' => 7,
  'permissions' => 'content_types',
), '', true, true);


$menus[0]->addMany($children,'Children');
unset($children);


/* ***************** MEDIA MENU ***************** */
$menus[1]= $xpdo->newObject('modMenu');
$menus[1]->fromArray(array (
  'text' => 'media',
  'parent' => '',
  'action' => '',
  'description' => 'media_desc',
  'icon' => '',
  'menuindex' => 1,
  'permissions' => 'file_manager',
), '', true, true);

/* file browser */
/* -- TODO: make this work! -- */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'file_browser',
  'description' => 'file_browser_desc',
  'parent' => 'media',
  'action' => 'media/browser',
  'icon' => '',
  'menuindex' => 0,
  'permissions' => 'file_manager',
), '', true, true);

/* media sources */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array(
  'menuindex'   => 1,
  'text'        => 'sources',
  'description' => 'sources_desc',
  'action'      => 'source',
  'permissions' => 'sources',
  'parent'      => '',
  'icon'        => 'images/icons/elements2.png',
), '', true, true);

$menus[1]->addMany($children,'Children');
unset($children);


/* ***************** APPS MENU ***************** */
$menus[2]= $xpdo->newObject('modMenu');
$menus[2]->fromArray(array (
  'text' => 'apps',
  'parent' => '',
  'action' => '',
  'description' => '',
  'icon' => '',
  'menuindex' => 2,
  'permissions' => 'components',
), '', true, true);

/* package management */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'installer',
  'parent' => '',
  'action' => 'workspaces',
  'description' => 'installer_desc',
  'icon' => '',
  'menuindex' => 0,
  'anchor_last' => true,
  'permissions' => 'packages',
), '', true, true);

$menus[2]->addMany($children,'Children');
unset($children);


/* ***************** ADMIN MENU ***************** */
$menus[3]= $xpdo->newObject('modMenu');
$menus[3]->fromArray(array (
  'text' => 'admin',
  'parent' => '',
  'action' => '',
  'description' => '',
  'icon' => '',
  'menuindex' => 3,
  'permissions' => 'menu_tools',
), '', true, true);
$children = array();

/* user management */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'users',
  'parent' => '',
  'action' => 'security/user',
  'description' => 'user_management_desc',
  'icon' => '',
  'menuindex' => 0,
  'permissions' => 'view_user',
), '', true, true);

/* clear cache */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'refresh_site',
  'parent' => '',
  'action' => '',
  'description' => 'refresh_site_desc',
  'icon' => '',
  'menuindex' => 1,
  'handler' => 'MODx.clearCache(); return false;',
  'permissions' => 'empty_cache',
), '', true, true);

/* remove locks */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'text' => 'remove_locks',
  'parent' => '',
  'action' => '',
  'description' => 'remove_locks_desc',
  'icon' => '',
  'menuindex' => 2,
  'handler' => '
MODx.msg.confirm({
    title: _(\'remove_locks\')
    ,text: _(\'confirm_remove_locks\')
    ,url: MODx.config.connectors_url+\'system/remove_locks.php\'
    ,params: {
        action: \'remove\'
    }
    ,listeners: {
        \'success\': {fn:function() { Ext.getCmp("modx-resource-tree").refresh(); },scope:this}
    }
});',
  'permissions' => 'remove_locks',
), '', true, true);

/* user group management */
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array (
  'text' => 'resource_groups',
  'parent' => 'user',
  'action' => 'security/resourcegroup',
  'description' => 'resource_groups_desc',
  'icon' => '',
  'menuindex' => 3,
  'permissions' => 'access_permissions',
), '', true, true);

/* flush permissions */
$children[4]= $xpdo->newObject('modMenu');
$children[4]->fromArray(array (
  'text' => 'flush_access',
  'parent' => 'tools',
  'action' => '',
  'description' => 'flush_access_desc',
  'icon' => '',
  'menuindex' => 4,
  'handler' => 'MODx.msg.confirm({
    title: _(\'flush_access\')
    ,text: _(\'flush_access_confirm\')
    ,url: MODx.config.connectors_url+\'security/access/index.php\'
    ,params: {
        action: \'flush\'
    }
    ,listeners: {
        \'success\': {fn:function() { location.href = \'./\'; },scope:this}
    }
});',
    'permissions' => 'access_permissions',
), '', true, true);

/* flush sessions */
$children[5]= $xpdo->newObject('modMenu');
$children[5]->fromArray(array (
  'text' => 'flush_sessions',
  'parent' => 'tools',
  'action' => '',
  'description' => 'flush_sessions_desc',
  'icon' => '',
  'menuindex' => 5,
  'handler' => 'MODx.msg.confirm({
    title: _(\'flush_sessions\')
    ,text: _(\'flush_sessions_confirm\')
    ,url: MODx.config.connectors_url+\'security/flush.php\'
    ,params: {
        action: \'flush\'
    }
    ,listeners: {
        \'success\': {fn:function() { location.href = \'./\'; },scope:this}
    }
});',
    'permissions' => 'flush_sessions',
), '', true, true);

$menus[3]->addMany($children,'Children');
unset($children);


/* ***************** REPORTS MENU ***************** */
$menus[4]= $xpdo->newObject('modMenu');
$menus[4]->fromArray(array(
  'text' => 'reports',
  'parent' => '',
  'action' => '',
  'description' => '',
  'icon' => '',
  'menuindex' => 4,
  'permissions' => 'menu_reports',
), '', true, true);
$children = array();

/* site schedule */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'site_schedule',
  'parent' => 'reports',
  'action' => 'resource/site_schedule',
  'description' => 'site_schedule_desc',
  'icon' => '',
  'menuindex' => 0,
  'permissions' => 'view_document',
), '', true, true);

/* manager actions */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'view_logging',
  'parent' => 'reports',
  'action' => 'system/logs',
  'description' => 'view_logging_desc',
  'icon' => '',
  'menuindex' => 1,
  'permissions' => 'logs',
), '', true, true);

/* error log */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'text' => 'eventlog_viewer',
  'parent' => 'reports',
  'action' => 'system/event',
  'description' => 'eventlog_viewer_desc',
  'icon' => '',
  'menuindex' => 2,
  'permissions' => 'view_eventlog',
), '', true, true);
        
/* system info */
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array (
  'text' => 'view_sysinfo',
  'parent' => 'reports',
  'action' => 'system/info',
  'description' => 'view_sysinfo_desc',
  'icon' => '',
  'menuindex' => 3,
  'permissions' => 'view_sysinfo',
), '', true, true);

$menus[4]->addMany($children,'Children');
unset($children);

return $menus;