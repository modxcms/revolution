<?php
/**
 * Adds all top Menu items to build
 *
 * @var xPDO $xpdo
 *
 * @package modx
 * @subpackage build
 */

use MODX\Revolution\modMenu;

$menus = array();

$menus[0]= $xpdo->newObject(modMenu::class);
$menus[0]->fromArray(array (
    'menuindex' => 0,
    'text' => 'topnav',
    'description' => 'topnav_desc',
    'parent' => '',
    'permissions' => '',
    'action' => '',
), '', true, true);

$menus[1]= $xpdo->newObject(modMenu::class);
$menus[1]->fromArray(array (
    'menuindex' => 0,
    'text' => 'usernav',
    'description' => 'usernav_desc',
    'parent' => '',
    'permissions' => '',
    'action' => '',
), '', true, true);


/* ********** CONTENT MENU ********** */
$topNavMenus[0]= $xpdo->newObject(modMenu::class);
$topNavMenus[0]->fromArray(array (
    'menuindex' => 0,
    'text' => 'site',
    'description' => '',
    'parent' => 'topnav',
    'permissions' => 'menu_site',
    'action' => '',
), '', true, true);

$children = array();

/* Clear Cache */
$children[0]= $xpdo->newObject(modMenu::class);
$children[0]->fromArray(array (
    'menuindex' => 0,
    'text' => 'refresh_site',
    'description' => 'refresh_site_desc',
    'parent' => 'site',
    'permissions' => 'empty_cache',
    'action' => '',
    'handler' => 'MODx.clearCache(); return false;',
), '', true, true);

/* Refresh URIs */
$childrenOfClearCache[0]= $xpdo->newObject(modMenu::class);
$childrenOfClearCache[0]->fromArray(array (
    'menuindex' => 0,
    'text' => 'refreshuris',
    'description' => 'refreshuris_desc',
    'parent' => '',
    'permissions' => 'empty_cache',
    'action' => '',
    'handler' => 'MODx.refreshURIs(); return false;',
), '', true, true);

$children[0]->addMany($childrenOfClearCache,'Children');

/* Remove Locks */
$children[1]= $xpdo->newObject(modMenu::class);
$children[1]->fromArray(array (
    'menuindex' => 1,
    'text' => 'remove_locks',
    'description' => 'remove_locks_desc',
    'parent' => 'site',
    'permissions' => 'remove_locks',
    'action' => '',
    'handler' => 'MODx.removeLocks();return false;',
), '', true, true);

/* Resource Groups */
$children[2]= $xpdo->newObject(modMenu::class);
$children[2]->fromArray(array (
    'menuindex' => 2,
    'text' => 'resource_groups',
    'description' => 'resource_groups_desc',
    'parent' => 'site',
    'permissions' => 'resourcegroup_view',
    'action' => 'security/resourcegroup',
), '', true, true);

/* Content Types */
$children[3]= $xpdo->newObject(modMenu::class);
$children[3]->fromArray(array (
    'menuindex' => 3,
    'text' => 'content_types',
    'description' => 'content_types_desc',
    'parent' => 'site',
    'permissions' => 'content_types',
    'action' => 'system/contenttype',
), '', true, true);

/* Site Schedule */
$children[4]= $xpdo->newObject(modMenu::class);
$children[4]->fromArray(array (
    'menuindex' => 4,
    'text' => 'site_schedule',
    'description' => 'site_schedule_desc',
    'parent' => 'site',
    'permissions' => 'view_document',
    'action' => 'resource/site_schedule',
), '', true, true);

/* Import HTML */
$children[5]= $xpdo->newObject(modMenu::class);
$children[5]->fromArray(array (
    'menuindex' => 5,
    'text' => 'import_site',
    'description' => 'import_site_desc',
    'parent' => 'site',
    'permissions' => 'import_static',
    'action' => 'system/import/html',
), '', true, true);

/* Import Static Resources */
$children[6]= $xpdo->newObject(modMenu::class);
$children[6]->fromArray(array (
    'menuindex' => 6,
    'text' => 'import_resources',
    'description' => 'import_resources_desc',
    'parent' => 'site',
    'permissions' => 'import_static',
    'action' => 'system/import',
), '', true, true);

$topNavMenus[0]->addMany($children,'Children');
unset($children);


/* ********** MEDIA MENU ********** */
$topNavMenus[1]= $xpdo->newObject(modMenu::class);
$topNavMenus[1]->fromArray(array (
    'menuindex' => 1,
    'text' => 'media',
    'description' => 'media_desc',
    'parent' => 'topnav',
    'permissions' => 'file_manager',
    'action' => '',
), '', true, true);

$children = array();

/* Media Browser */
$children[0]= $xpdo->newObject(modMenu::class);
$children[0]->fromArray(array (
    'menuindex' => 0,
    'text' => 'file_browser',
    'description' => 'file_browser_desc',
    'parent' => 'media',
    'permissions' => 'file_manager',
    'action' => 'media/browser',
), '', true, true);

/* Media Sources */
$children[1]= $xpdo->newObject(modMenu::class);
$children[1]->fromArray(array(
    'menuindex' => 1,
    'text' => 'sources',
    'description' => 'sources_desc',
    'parent' => 'media',
    'permissions' => 'sources',
    'action' => 'source',
), '', true, true);

$topNavMenus[1]->addMany($children,'Children');
unset($children);


/* ********** APPS MENU ********** */
$topNavMenus[2]= $xpdo->newObject(modMenu::class);
$topNavMenus[2]->fromArray(array (
    'menuindex' => 2,
    'text' => 'components',
    'description' => '',
    'parent' => 'topnav',
    'permissions' => '',
    'action' => '',
), '', true, true);

$children = array();

/* Installer */
$children[0]= $xpdo->newObject(modMenu::class);
$children[0]->fromArray(array (
    'menuindex' => 0,
    'text' => 'installer',
    'description' => 'installer_desc',
    'parent' => 'components',
    'permissions' => 'packages',
    'action' => 'workspaces',
), '', true, true);

/* System Settings */
$children[1]= $xpdo->newObject(modMenu::class);
$children[1]->fromArray(array (
    'menuindex' => 1,
    'text' => 'system_settings_components',
    'description' => 'system_settings_components_desc',
    'parent' => 'components',
    'permissions' => 'settings',
    'action' => 'system/settings',
), '', true, true);

/* Lexicons */
$children[2]= $xpdo->newObject(modMenu::class);
$children[2]->fromArray(array (
    'menuindex' => 2,
    'text' => 'lexicon_components_management',
    'description' => 'lexicon_components_management_desc',
    'parent' => 'components',
    'permissions' => 'lexicons',
    'action' => 'workspaces/lexicon',
), '', true, true);

/* Namespaces */
$children[3]= $xpdo->newObject(modMenu::class);
$children[3]->fromArray(array (
    'menuindex' => 3,
    'text' => 'namespaces',
    'description' => 'namespaces_desc',
    'parent' => 'components',
    'permissions' => 'namespaces',
    'action' => 'workspaces/namespace',
), '', true, true);

$topNavMenus[2]->addMany($children,'Children');
unset($children);


/* ********** Access & Users ********** */
$topNavMenus[3]= $xpdo->newObject(modMenu::class);
$topNavMenus[3]->fromArray(array (
    'menuindex' => 3,
    'text' => 'access',
    'description' => '',
    'parent' => 'topnav',
    'permissions' => 'access_permissions',
    'action' => '',
), '', true, true);

$children = array();

/* ACLs */
$children[0]= $xpdo->newObject(modMenu::class);
$children[0]->fromArray(array (
    'menuindex' => 0,
    'text' => 'acls',
    'description' => 'acls_desc',
    'parent' => 'access',
    'permissions' => 'access_permissions',
    'action' => 'security/permission',
), '', true, true);

/* Flush Permissions */
$children[1]= $xpdo->newObject(modMenu::class);
$children[1]->fromArray(array (
    'menuindex' => 1,
    'text' => 'flush_access',
    'description' => 'flush_access_desc',
    'parent' => 'access',
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

/* Manage Users */
$children[2]= $xpdo->newObject(modMenu::class);
$children[2]->fromArray(array (
    'menuindex' => 2,
    'text' => 'users',
    'description' => 'user_management_desc',
    'parent' => 'access',
    'permissions' => 'view_user',
    'action' => 'security/user',
), '', true, true);

/* Flush Sessions */
$children[3]= $xpdo->newObject(modMenu::class);
$children[3]->fromArray(array (
    'menuindex' => 3,
    'text' => 'flush_sessions',
    'description' => 'flush_sessions_desc',
    'parent' => 'access',
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

$topNavMenus[3]->addMany($children,'Children');
unset($children);


/* ********** MANAGER ********** */
$topNavMenus[4]= $xpdo->newObject(modMenu::class);
$topNavMenus[4]->fromArray(array (
    'menuindex' => 4,
    'text' => 'manager',
    'description' => '',
    'parent' => 'topnav',
    'permissions' => '',
    'action' => '',
), '', true, true);

$children = array();

/* System Settings */
$children[0]= $xpdo->newObject(modMenu::class);
$children[0]->fromArray(array (
    'menuindex' => 0,
    'text' => 'system_settings',
    'description' => 'system_settings_desc',
    'parent' => 'manager',
    'permissions' => 'settings',
    'action' => 'system/settings',
), '', true, true);

/* Dashboards */
$children[1]= $xpdo->newObject(modMenu::class);
$children[1]->fromArray(array (
    'menuindex' => 1,
    'text' => 'dashboards',
    'description' => 'dashboards_desc',
    'parent' => 'manager',
    'permissions' => 'dashboards',
    'action' => 'system/dashboards',
), '', true, true);

/* Manager Menus */
$children[2]= $xpdo->newObject(modMenu::class);
$children[2]->fromArray(array (
    'menuindex' => 2,
    'text' => 'edit_menu',
    'description' => 'edit_menu_desc',
    'parent' => 'manager',
    'permissions' => 'actions',
    'action' => 'system/action',
), '', true, true);

/* Manager Customization */
$children[3]= $xpdo->newObject(modMenu::class);
$children[3]->fromArray(array (
    'menuindex' => 3,
    'text' => 'form_customization',
    'description' => 'form_customization_desc',
    'parent' => 'manager',
    'permissions' => 'customize_forms',
    'action' => 'security/forms',
), '', true, true);

/* Contexts */
$children[4]= $xpdo->newObject(modMenu::class);
$children[4]->fromArray(array (
    'menuindex' => 4,
    'text' => 'contexts',
    'description' => 'contexts_desc',
    'parent' => 'manager',
    'permissions' => 'view_context',
    'action' => 'context',
), '', true, true);

/* Property Sets */
$children[5]= $xpdo->newObject(modMenu::class);
$children[5]->fromArray(array (
    'menuindex' => 5,
    'text' => 'propertysets',
    'description' => 'propertysets_desc',
    'parent' => 'manager',
    'permissions' => 'property_sets',
    'action' => 'element/propertyset',
), '', true, true);

/* Lexicons */
$children[6]= $xpdo->newObject(modMenu::class);
$children[6]->fromArray(array (
    'menuindex' => 6,
    'text' => 'lexicon_management',
    'description' => 'lexicon_management_desc',
    'parent' => 'manager',
    'permissions' => 'lexicons',
    'action' => 'workspaces/lexicon',
), '', true, true);

/* Language */
$children[7]= $xpdo->newObject(modMenu::class);
$children[7]->fromArray(array(
    'menuindex' => 7,
    'text' => 'language',
    'description' => 'language_desc',
    'parent' => 'manager',
    'permissions' => 'language',
    'action' => ''
), '', true, true);

$topNavMenus[4]->addMany($children,'Children');
unset($children);


/* ********** USER MENU ********** */
$userNavMenus[0]= $xpdo->newObject(modMenu::class);
$userNavMenus[0]->fromArray(array(
    'menuindex' => 0,
    'text' => 'user',
    'description' => '',
    'parent' => 'usernav',
    'permissions' => 'menu_user',
    'action' => '',
    'icon' => '<span id="user-avatar">{$userImage}</span> <span id="user-username">{$username}</span>',
), '', true, true);

$children = array();

/* Edit Account */
$children[0]= $xpdo->newObject(modMenu::class);
$children[0]->fromArray(array (
    'menuindex' => 0,
    'text' => 'profile',
    'description' => 'profile_desc',
    'parent' => 'user',
    'permissions' => 'change_profile',
    'action' => 'security/profile',
), '', true, true);

/* Messages */
$children[1]= $xpdo->newObject(modMenu::class);
$children[1]->fromArray(array (
    'menuindex' => 1,
    'text' => 'messages',
    'description' => 'messages_desc',
    'parent' => 'user',
    'permissions' => 'messages',
    'action' => 'security/message',
), '', true, true);

/* Logout */
$children[2]= $xpdo->newObject(modMenu::class);
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


/* ********** SYSTEM MENU ********** */
$userNavMenus[1]= $xpdo->newObject(modMenu::class);
$userNavMenus[1]->fromArray(array(
    'menuindex' => 1,
    'text' => 'admin',
    'description' => '',
    'parent' => 'usernav',
    'permissions' => 'settings',
    'action' => '',
    'icon' => '<i class="icon-gear icon icon-large"></i>',
), '', true, true);

$children = array();

/* Manager Actions */
$children[2]= $xpdo->newObject(modMenu::class);
$children[2]->fromArray(array (
    'menuindex' => 2,
    'text' => 'view_logging',
    'description' => 'view_logging_desc',
    'parent' => 'admin',
    'permissions' => 'logs',
    'action' => 'system/logs',
), '', true, true);

/* Error Log */
$children[3]= $xpdo->newObject(modMenu::class);
$children[3]->fromArray(array (
    'menuindex' => 3,
    'text' => 'eventlog_viewer',
    'description' => 'eventlog_viewer_desc',
    'parent' => 'admin',
    'permissions' => 'view_eventlog',
    'action' => 'system/event',
), '', true, true);

/* System Info */
$children[4]= $xpdo->newObject(modMenu::class);
$children[4]->fromArray(array (
    'menuindex' => 4,
    'text' => 'view_sysinfo',
    'description' => 'view_sysinfo_desc',
    'parent' => 'admin',
    'permissions' => 'view_sysinfo',
    'action' => 'system/info',
), '', true, true);

$userNavMenus[1]->addMany($children,'Children');
unset($children);


/* ********** ABOUT MENU ********** */
$userNavMenus[2]= $xpdo->newObject(modMenu::class);
$userNavMenus[2]->fromArray(array(
    'menuindex' => 8,
    'text' => 'about',
    'description' => '',
    'parent' => 'usernav',
    'permissions' => 'help',
    'action' => 'help',
    'icon' => '<i class="icon-question-circle icon icon-large"></i>',
), '', true, true);

/* Add mainnav and usernav menu children */
$menus[0]->addMany($topNavMenus,'Children');
$menus[1]->addMany($userNavMenus,'Children');

unset($topNavMenus, $userNavMenus);

return $menus;
