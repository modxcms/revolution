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

$menus[0]= $xpdo->newObject('modMenu');
$menus[0]->fromArray(array (
    'menuindex' => 0,
    'text' => 'topnav',
    'description' => 'topnav_desc',
    'parent' => '',
    'permissions' => '',
    'action' => '',
), '', true, true);

$menus[1]= $xpdo->newObject('modMenu');
$menus[1]->fromArray(array (
    'menuindex' => 0,
    'text' => 'usernav',
    'description' => 'usernav_desc',
    'parent' => '',
    'permissions' => '',
    'action' => '',
), '', true, true);


/* ***************** CONTENT MENU ***************** */
$topNavMenus[0]= $xpdo->newObject('modMenu');
$topNavMenus[0]->fromArray(array (
  'menuindex' => 0,
  'text' => 'site',
  'description' => '',
  'parent' => 'topnav',
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
  'parent' => 'site',
  'permissions' => 'new_document',
  'action' => 'resource/create',
), '', true, true);

/* Preview */
$children[4]= $xpdo->newObject('modMenu');
$children[4]->fromArray(array (
  'menuindex' => 4,
  'text' => 'preview',
  'description' => 'preview_desc',
  'parent' => 'site',
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
  'parent' => 'site',
  'permissions' => 'import_static',
  'action' => 'system/import/html',
), '', true, true);

/* Import Static Resources */
$children[6]= $xpdo->newObject('modMenu');
$children[6]->fromArray(array (
  'menuindex' => 6,
  'text' => 'import_resources',
  'description' => 'import_resources_desc',
  'parent' => 'site',
  'permissions' => 'import_static',
  'action' => 'system/import',
), '', true, true);

/* Manage Resource Groups */
$children[7]= $xpdo->newObject('modMenu');
$children[7]->fromArray(array (
  'menuindex' => 7,
  'text' => 'resource_groups',
  'description' => 'resource_groups_desc',
  'parent' => 'site',
  'permissions' => 'access_permissions',
  'action' => 'security/resourcegroup',
), '', true, true);

/* Content Types */
$children[8]= $xpdo->newObject('modMenu');
$children[8]->fromArray(array (
  'menuindex' => 8,
  'text' => 'content_types',
  'description' => 'content_types_desc',
  'parent' => 'site',
  'permissions' => 'content_types',
  'action' => 'system/contenttype',
), '', true, true);

$topNavMenus[0]->addMany($children,'Children');
unset($children);


/* ***************** MEDIA MENU ***************** */
$topNavMenus[1]= $xpdo->newObject('modMenu');
$topNavMenus[1]->fromArray(array (
  'menuindex' => 1,
  'text' => 'media',
  'description' => 'media_desc',
  'parent' => 'topnav',
  'permissions' => 'file_manager',
  'action' => '',
), '', true, true);

/* Media Browser */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'menuindex' => 0,
  'text' => 'file_browser',
  'description' => 'file_browser_desc',
  'parent' => 'media',
  'permissions' => 'file_manager',
  'action' => 'media/browser',
), '', true, true);

/* Media Drivers */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array(
  'menuindex'   => 1,
  'text'        => 'sources',
  'description' => 'sources_desc',
  'parent'      => 'media',
  'permissions' => 'sources',
  'action'      => 'source',
), '', true, true);

$topNavMenus[1]->addMany($children,'Children');
unset($children);


/* ***************** APPS MENU ***************** */
$topNavMenus[2]= $xpdo->newObject('modMenu');
$topNavMenus[2]->fromArray(array (
  'menuindex' => 2,
  'text' => 'components',
  'description' => '',
  'parent' => 'topnav',
  'permissions' => 'components',
  'action' => '',
), '', true, true);

/* Installer */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'menuindex' => 10,
  'text' => 'installer',
  'description' => 'installer_desc',
  'parent' => 'components',
  'permissions' => 'packages',
  'action' => 'workspaces',
), '', true, true);

$topNavMenus[2]->addMany($children,'Children');
unset($children);


/* ***************** ADMIN MENU ***************** */
$topNavMenus[3]= $xpdo->newObject('modMenu');
$topNavMenus[3]->fromArray(array (
  'menuindex' => 3,
  'text' => 'manage',
  'description' => '',
  'parent' => 'topnav',
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
  'parent' => 'manage',
  'permissions' => 'view_user',
  'action' => 'security/user',
), '', true, true);

/* Clear Cache */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'menuindex' => 1,
  'text' => 'refresh_site',
  'description' => 'refresh_site_desc',
  'parent' => 'manage',
  'permissions' => 'empty_cache',
  'action' => '',
  'handler' => 'MODx.clearCache(); return false;',
), '', true, true);

/* Refresh URIs */
$childrenOfClearCache[0]= $xpdo->newObject('modMenu');
$childrenOfClearCache[0]->fromArray(array (
  'menuindex' => 0,
  'text' => 'refreshuris',
  'description' => 'refreshuris_desc',
  'parent' => '',
  'permissions' => 'empty_cache',
  'action' => '',
  'handler' => 'MODx.refreshURIs(); return false;',
), '', true, true);

$children[1]->addMany($childrenOfClearCache, 'Children');

/* Remove Locks */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'menuindex' => 2,
  'text' => 'remove_locks',
  'description' => 'remove_locks_desc',
  'parent' => 'manage',
  'permissions' => 'remove_locks',
  'action' => '',
  'handler' => 'MODx.removeLocks();return false;',
), '', true, true);

/* Flush Permissions */
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array (
  'menuindex' => 3,
  'text' => 'flush_access',
  'description' => 'flush_access_desc',
  'parent' => 'manage',
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
        \'success\': {fn:function() { location.href = \'./\'; },scope:this},
        \'failure\': {fn:function(response) { Ext.MessageBox.alert(\'failure\', response.responseText); },scope:this},
    }
});',
), '', true, true);

/* Flush Sessions */
$children[4]= $xpdo->newObject('modMenu');
$children[4]->fromArray(array (
  'menuindex' => 4,
  'text' => 'flush_sessions',
  'description' => 'flush_sessions_desc',
  'parent' => 'manage',
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
  'parent' => 'manage',
  'permissions' => 'menu_reports',
  'action' => '',
), '', true, true);

/* site schedule */
$childrenOfReports[0]= $xpdo->newObject('modMenu');
$childrenOfReports[0]->fromArray(array (
        'menuindex' => 0,
        'text' => 'site_schedule',
        'description' => 'site_schedule_desc',
        'parent' => '',
        'permissions' => 'view_document',
        'action' => 'resource/site_schedule',
    ), '', true, true);

/* manager actions */
$childrenOfReports[1]= $xpdo->newObject('modMenu');
$childrenOfReports[1]->fromArray(array (
    'menuindex' => 1,
    'text' => 'view_logging',
    'description' => 'view_logging_desc',
    'parent' => '',
    'permissions' => 'logs',
    'action' => 'system/logs',
), '', true, true);

/* error log */
$childrenOfReports[2]= $xpdo->newObject('modMenu');
$childrenOfReports[2]->fromArray(array (
    'menuindex' => 2,
    'text' => 'eventlog_viewer',
    'description' => 'eventlog_viewer_desc',
    'parent' => '',
    'permissions' => 'view_eventlog',
    'action' => 'system/event',
), '', true, true);

/* system info */
$childrenOfReports[3]= $xpdo->newObject('modMenu');
$childrenOfReports[3]->fromArray(array (
    'menuindex' => 3,
    'text' => 'view_sysinfo',
    'description' => 'view_sysinfo_desc',
    'parent' => 'reports',
    'permissions' => 'view_sysinfo',
    'action' => 'system/info',
), '', true, true);

$children[5]->addMany($childrenOfReports, 'Children');


$topNavMenus[3]->addMany($children,'Children');
unset($children, $childrenOfReports);


/* ***************** USER MENU ***************** */
$userNavMenus[0]= $xpdo->newObject('modMenu');
$userNavMenus[0]->fromArray(array(
  'menuindex' => 5,
  'text' => 'user',
  'description' => '',
  'parent' => 'usernav',
  'permissions' => 'menu_user',
  'action' => '',
  'icon' => '<span id="user-avatar">{$userImage}</span> <span id="user-username">{$username}</span>',
), '', true, true);
$children = array();

/* edit account */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'menuindex' => 0,
  'text' => 'profile',
  'description' => 'profile_desc',
  'parent' => 'user',
  'permissions' => 'change_profile',
  'action' => 'security/profile',
), '', true, true);

/* messages */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'menuindex' => 1,
  'text' => 'messages',
  'description' => 'messages_desc',
  'parent' => 'user',
  'permissions' => 'messages',
  'action' => 'security/message',
), '', true, true);

/* logout */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'menuindex' => 2,
  'text' => 'logout',
  'description' => 'logout_desc',
  'parent' => 'user',
  'permissions' => 'logout',
  'action' => 'security/logout',
  'handler' => 'MODx.logout(); return false;',
), '', true, true);

$userNavMenus[0]->addMany($children,'Children');
unset($children);

/* ***************** ADMIN/SETTINGS MENU ***************** */
$userNavMenus[1]= $xpdo->newObject('modMenu');
$userNavMenus[1]->fromArray(array(
  'menuindex' => 6,
  'text' => 'admin',
  'description' => '',
  'parent' => 'usernav',
  'permissions' => 'settings',
  'action' => '',
  'icon' => '<i class="icon-gear icon icon-large"></i>',
), '', true, true);
$children = array();

/* system settings */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'menuindex' => 0,
  'text' => 'system_settings',
  'description' => 'system_settings_desc',
  'parent' => 'admin',
  'permissions' => 'settings',
  'action' => 'system/settings',
), '', true, true);

/* customize manager */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'menuindex' => 1,
  'text' => 'bespoke_manager',
  'description' => 'bespoke_manager_desc',
  'parent' => 'admin',
  'permissions' => 'customize_forms',
  'action' => 'security/forms',
), '', true, true);

/* Dashboards */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'menuindex' => 2,
  'text' => 'dashboards',
  'description' => 'dashboards_desc',
  'parent' => 'admin',
  'permissions' => 'dashboards',
  'action' => 'system/dashboards',
), '', true, true);

/* Contexts */
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array (
  'menuindex' => 3,
  'text' => 'contexts',
  'description' => 'contexts_desc',
  'parent' => 'admin',
  'permissions' => 'view_context',
  'action' => 'context',
), '', true, true);

/* Manager Menus */
$children[4]= $xpdo->newObject('modMenu');
$children[4]->fromArray(array (
  'menuindex' => 4,
  'text' => 'edit_menu',
  'description' => 'edit_menu_desc',
  'parent' => 'admin',
  'permissions' => 'actions',
  'action' => 'system/action',
), '', true, true);

/* ACLs */
$children[5]= $xpdo->newObject('modMenu');
$children[5]->fromArray(array (
  'menuindex' => 5,
  'text' => 'acls',
  'description' => 'acls_desc',
  'parent' => 'admin',
  'permissions' => 'access_permissions',
  'action' => 'security/permission',
), '', true, true);

/* Property Sets */
$children[6]= $xpdo->newObject('modMenu');
$children[6]->fromArray(array (
  'menuindex' => 6,
  'text' => 'propertysets',
  'description' => 'propertysets_desc',
  'parent' => 'admin',
  'permissions' => 'property_sets',
  'action' => 'element/propertyset',
), '', true, true);

/* Lexicons */
$children[7]= $xpdo->newObject('modMenu');
$children[7]->fromArray(array (
  'menuindex' => 7,
  'text' => 'lexicon_management',
  'description' => 'lexicon_management_desc',
  'parent' => 'admin',
  'permissions' => 'lexicons',
  'action' => 'workspaces/lexicon',
), '', true, true);

/* Namespaces */
$children[8]= $xpdo->newObject('modMenu');
$children[8]->fromArray(array (
  'menuindex' => 8,
  'text' => 'namespaces',
  'description' => 'namespaces_desc',
  'parent' => 'admin',
  'permissions' => 'namespaces',
  'action' => 'workspaces/namespace',
), '', true, true);

$userNavMenus[1]->addMany($children,'Children');
unset($children);

/* ***************** ADMIN/ABOUT MENU ***************** */
$userNavMenus[2]= $xpdo->newObject('modMenu');
$userNavMenus[2]->fromArray(array(
  'menuindex' => 7,
  'text' => 'about',
  'description' => '',
  'parent' => 'usernav',
  'permissions' => 'help',
  'action' => 'help',
  'icon' => '<i class="icon-question-circle icon icon-large"></i>',
), '', true, true);
$children = array();

/* add topnav and usernav menu children */
$menus[0]->addMany($topNavMenus, 'Children');
$menus[1]->addMany($userNavMenus, 'Children');

unset($topNavMenus, $userNavMenus);

return $menus;
