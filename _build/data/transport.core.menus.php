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
  'menuindex' => 0,
  'text' => 'content',
  'description' => '',
  'parent' => '',
  'permissions' => 'menu_site',
  'action' => '',
), '', true, true);

$children = array();

/* New Resource */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'menuindex' => 0,
  'text' => 'new_resource',
  'description' => 'new_resource_desc',
  'parent' => '',
  'permissions' => 'new_document',
  'action' => 'resource/create',
), '', true, true);

    /* new static resource 
    $children[1]= $xpdo->newObject('modMenu');
    $children[1]->fromArray(array (
      'menuindex' => 10,
      'text' => 'new_static_resource',
      'description' => 'new_static_resource_desc',
      'parent' => 'new_resource',
      'permissions' => 'new_static_resource',
      'action' => 'resource/create',
      'params' => '&class_key=modStaticResource',
    ), '', true, true);

    /* new weblink resource 
    $children[2]= $xpdo->newObject('modMenu');
    $children[2]->fromArray(array (
      'menuindex' => 11,
      'text' => 'new_weblink',
      'description' => 'new_weblink_desc',
      'parent' => 'new_resource',
      'permissions' => 'new_weblink',
      'action' => 'resource/create',
      'params' => '&class_key=modWebLink',
    ), '', true, true);

    /* new symlink resource 
    $children[3]= $xpdo->newObject('modMenu');
    $children[3]->fromArray(array (
      'menuindex' => 12,
      'text' => 'new_symlink',
      'description' => 'new_symlink_desc',
      'parent' => 'new_resource',
      'permissions' => 'new_symlink',
      'action' => 'resource/create',
      'params' => '&class_key=modSymLink',
    ), '', true, true);
*/

/* Preview */
$children[4]= $xpdo->newObject('modMenu');
$children[4]->fromArray(array (
  'menuindex' => 4,
  'text' => 'preview',
  'description' => 'preview_desc',
  'parent' => '',
  'permissions' => '',
  'action' => '',
  'handler' => 'MODx.preview(); return false;',
), '', true, true);

/* Import HTML */
$children[5]= $xpdo->newObject('modMenu');
$children[5]->fromArray(array (
  'menuindex' => 5,
  'text' => 'import_site',
  'description' => 'import_site_desc',
  'parent' => '',
  'permissions' => 'import_static',
  'action' => 'system/import/html',
), '', true, true);

/* Import Static Resources */
$children[6]= $xpdo->newObject('modMenu');
$children[6]->fromArray(array (
  'menuindex' => 6,
  'text' => 'import_resources',
  'description' => 'import_resources_desc',
  'parent' => '',
  'permissions' => 'import_static',
  'action' => 'system/import',
), '', true, true);

/* Manage Resource Groups */
$children[7]= $xpdo->newObject('modMenu');
$children[7]->fromArray(array (
  'menuindex' => 7,
  'text' => 'resource_groups',
  'description' => 'resource_groups_desc',
  'parent' => '',
  'permissions' => 'access_permissions',
  'action' => 'security/resourcegroup',
), '', true, true);

/* Content Types */
$children[8]= $xpdo->newObject('modMenu');
$children[8]->fromArray(array (
  'menuindex' => 8,
  'text' => 'content_types',
  'description' => 'content_types_desc',
  'parent' => 'content',
  'permissions' => 'content_types',
  'action' => 'system/contenttype',
), '', true, true);

$menus[0]->addMany($children,'Children');
unset($children);


/* ***************** MEDIA MENU ***************** */
$menus[1]= $xpdo->newObject('modMenu');
$menus[1]->fromArray(array (
  'menuindex' => 1,
  'text' => 'media',
  'description' => 'media_desc',
  'parent' => '',
  'permissions' => 'file_manager',
  'action' => '',
), '', true, true);

/* Media Browser */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'menuindex' => 0,
  'text' => 'file_browser',
  'description' => 'file_browser_desc',
  'parent' => '',
  'permissions' => 'file_manager',
  'action' => 'media/browser',
), '', true, true);

/* Media Drivers */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array(
  'menuindex'   => 1,
  'text'        => 'sources',
  'description' => 'sources_desc',
  'parent'      => '',
  'permissions' => 'sources',
  'action'      => 'source',
), '', true, true);

$menus[1]->addMany($children,'Children');
unset($children);


/* ***************** APPS MENU ***************** */
$menus[2]= $xpdo->newObject('modMenu');
$menus[2]->fromArray(array (
  'menuindex' => 2,
  'text' => 'apps',
  'description' => '',
  'parent' => '',
  'permissions' => 'components',
  'action' => '',
), '', true, true);

/* Installer */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'menuindex' => 0,
  'text' => 'installer',
  'description' => 'installer_desc',
  'parent' => '',
  'permissions' => 'packages',
  'action' => 'workspaces',
), '', true, true);

$menus[2]->addMany($children,'Children');
unset($children);


/* ***************** ADMIN MENU ***************** */
$menus[3]= $xpdo->newObject('modMenu');
$menus[3]->fromArray(array (
  'menuindex' => 3,
  'text' => 'manage',
  'description' => '',
  'parent' => '',
  'permissions' => 'menu_tools',
  'action' => '',
), '', true, true);
$children = array();

/* Manage Users */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'menuindex' => 0,
  'text' => 'users',
  'description' => 'user_management_desc',
  'parent' => '',
  'permissions' => 'view_user',
  'action' => 'security/user',
), '', true, true);

/* Clear Cache */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'menuindex' => 1,
  'text' => 'refresh_site',
  'description' => 'refresh_site_desc',
  'parent' => '',
  'permissions' => 'empty_cache',
  'action' => '',
  'handler' => 'MODx.clearCache(); return false;',
), '', true, true);

/* Remove Locks */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'menuindex' => 2,
  'text' => 'remove_locks',
  'description' => 'remove_locks_desc',
  'parent' => '',
  'permissions' => 'remove_locks',
  'action' => '',
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
), '', true, true);

/* Flush Permissions */
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array (
  'menuindex' => 3,
  'text' => 'flush_access',
  'description' => 'flush_access_desc',
  'parent' => 'tools',
  'permissions' => 'access_permissions',
  'action' => '',
  'handler' => 'MODx.msg.confirm({
    title: _(\'flush_access\')
    ,text: _(\'flush_access_confirm\')
    ,url: MODx.config.connector_url
    ,params: {
        action: \'security/access/flush\'
    }
    ,listeners: {
        \'success\': {fn:function() { location.href = \'./\'; },scope:this}
    }
});',
), '', true, true);

/* Flush Sessions */
$children[4]= $xpdo->newObject('modMenu');
$children[4]->fromArray(array (
  'menuindex' => 4,
  'text' => 'flush_sessions',
  'description' => 'flush_sessions_desc',
  'parent' => 'tools',
  'permissions' => 'flush_sessions',
  'action' => '',
  'handler' => 'MODx.msg.confirm({
    title: _(\'flush_sessions\')
    ,text: _(\'flush_sessions_confirm\')
    ,url: MODx.config.connector_url
    ,params: {
        action: \'security/flush\'
    }
    ,listeners: {
        \'success\': {fn:function() { location.href = \'./\'; },scope:this}
    }
});',
), '', true, true);

/* Reports */
$children[5]= $xpdo->newObject('modMenu');
$children[5]->fromArray(array (
  'menuindex' => 5,
  'text' => 'reports',
  'description' => 'reports_desc',
  'parent' => '',
  'permissions' => 'menu_reports',
  'action' => 'reports',
), '', true, true);


$menus[3]->addMany($children,'Children');
unset($children);


/* ***************** REPORTS MENU ***************** 
$menus[4]= $xpdo->newObject('modMenu');
$menus[4]->fromArray(array(
  'menuindex' => 4,
  'text' => 'reports',
  'description' => '',
  'parent' => '',
  'permissions' => 'menu_reports',
  'action' => '',
), '', true, true);
$children = array();

/* site schedule 
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'menuindex' => 0,
  'text' => 'site_schedule',
  'description' => 'site_schedule_desc',
  'parent' => '',
  'permissions' => 'view_document',
  'action' => 'resource/site_schedule',
), '', true, true);

/* manager actions 
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'menuindex' => 1,
  'text' => 'view_logging',
  'description' => 'view_logging_desc',
  'parent' => '',
  'permissions' => 'logs',
  'action' => 'system/logs',
), '', true, true);

/* error log 
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'menuindex' => 2,
  'text' => 'eventlog_viewer',
  'description' => 'eventlog_viewer_desc',
  'parent' => '',
  'permissions' => 'view_eventlog',
  'action' => 'system/event',
), '', true, true);
        
/* system info 
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array (
  'menuindex' => 3,
  'text' => 'view_sysinfo',
  'description' => 'view_sysinfo_desc',
  'parent' => 'reports',
  'permissions' => 'view_sysinfo',
  'action' => 'system/info',
), '', true, true);

$menus[4]->addMany($children,'Children');
unset($children);
*/

return $menus;