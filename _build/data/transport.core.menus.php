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

/* ***************** DASHBOARD MENU ***************** */

$menus[0]= $xpdo->newObject('modMenu');
$menus[0]->fromArray(array (
  'text' => 'dashboard',
  'parent' => '',
  'action' => 'welcome',
  'description' => '',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 0,
  'handler' => 'MODx.loadPage(""); return false;',
  'permissions' => 'home',
), '', true, true);

$children = array();

/* dashboards */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'dashboards',
  'parent' => 'reports',
  'action' => 'system/dashboards',
  'description' => 'dashboards_desc',
  'icon' => 'images/icons/information.png',
  'menuindex' => 0,
  'permissions' => 'dashboards',
), '', true, true);

$menus[0]->addMany($children,'Children');
unset($children);



/* ***************** SITE MENU ***************** */
$menus[1]= $xpdo->newObject('modMenu');
$menus[1]->fromArray(array (
  'text' => 'site',
  'parent' => '',
  'action' => '',
  'description' => '',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 1,
  'permissions' => 'menu_site',
), '', true, true);

$children = array();

/* preview */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'preview',
  'parent' => 'site',
  'action' => '',
  'description' => 'preview_desc',
  'icon' => 'images/icons/show.gif',
  'menuindex' => 0,
  'handler' => 'MODx.preview(); return false;',
), '', true, true);


/* clear cache */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'refresh_site',
  'parent' => 'site',
  'action' => '',
  'description' => 'refresh_site_desc',
  'icon' => 'images/icons/refresh.png',
  'menuindex' => 1,
  'handler' => 'MODx.clearCache(); return false;',
  'permissions' => 'empty_cache',
), '', true, true);

/* remove locks */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'text' => 'remove_locks',
  'parent' => 'site',
  'action' => '',
  'description' => 'remove_locks_desc',
  'icon' => 'images/ext/default/grid/hmenu-unlock.png',
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

/* search */
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array (
  'text' => 'search',
  'parent' => 'site',
  'action' => 'search',
  'description' => 'search_desc',
  'icon' => 'images/icons/context_view.gif',
  'menuindex' => 3,
  'permissions' => 'search',
), '', true, true);

/* new document resource */
$children[4]= $xpdo->newObject('modMenu');
$children[4]->fromArray(array (
  'text' => 'new_document',
  'parent' => 'site',
  'action' => 'resource/create',
  'description' => 'new_document_desc',
  'icon' => 'images/icons/folder_page_add.png',
  'menuindex' => 4,
  'permissions' => 'new_document',
), '', true, true);

/* new weblink resource */
$children[5]= $xpdo->newObject('modMenu');
$children[5]->fromArray(array (
  'text' => 'new_weblink',
  'parent' => 'site',
  'action' => 'resource/create',
  'description' => 'new_weblink_desc',
  'icon' => 'images/icons/link_add.png',
  'menuindex' => 5,
  'params' => '&class_key=modWebLink',
  'permissions' => 'new_weblink',
), '', true, true);

/* new symlink resource */
$children[6]= $xpdo->newObject('modMenu');
$children[6]->fromArray(array (
  'text' => 'new_symlink',
  'parent' => 'site',
  'action' => 'resource/create',
  'description' => 'new_symlink_desc',
  'icon' => 'images/icons/link_add.png',
  'menuindex' => 6,
  'params' => '&class_key=modSymLink',
  'permissions' => 'new_symlink',
), '', true, true);

/* new static resource */
$children[7]= $xpdo->newObject('modMenu');
$children[7]->fromArray(array (
  'text' => 'new_static_resource',
  'parent' => 'site',
  'action' => 'resource/create',
  'description' => 'new_static_resource_desc',
  'icon' => 'images/icons/link_add.png',
  'menuindex' => 7,
  'params' => '&class_key=modStaticResource',
  'permissions' => 'new_static_resource',
), '', true, true);

/* logout */
$children[8]= $xpdo->newObject('modMenu');
$children[8]->fromArray(array (
  'text' => 'logout',
  'parent' => 'site',
  'action' => '',
  'description' => 'logout_desc',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 8,
  'handler' => 'MODx.logout(); return false;',
  'permissions' => 'logout',
), '', true, true);


$menus[1]->addMany($children,'Children');
unset($children);





/* ***************** COMPONENTS MENU ***************** */
$menus[2]= $xpdo->newObject('modMenu');
$menus[2]->fromArray(array (
  'text' => 'components',
  'parent' => '',
  'action' => '',
  'description' => '',
  'icon' => 'images/icons/plugin.gif',
  'menuindex' => 2,
  'permissions' => 'components',
), '', true, true);


/* ****************** SECURITY MENU ****************** */
$menus[3]= $xpdo->newObject('modMenu');
$menus[3]->fromArray(array (
  'text' => 'security',
  'parent' => '',
  'action' => '',
  'description' => '',
  'icon' => 'images/icons/lock.gif',
  'menuindex' => 3,
  'permissions' => 'menu_security',
), '', true, true);
$children = array();

/* user management */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'user_management',
  'parent' => 'security',
  'action' => 'security/user',
  'description' => 'user_management_desc',
  'icon' => 'images/icons/user.gif',
  'menuindex' => 0,
  'permissions' => 'view_user',
), '', true, true);

/* user group management */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'user_group_management',
  'parent' => 'security',
  'action' => 'security/permission',
  'description' => 'user_group_management_desc',
  'icon' => 'images/icons/mnu_users.gif',
  'menuindex' => 1,
  'permissions' => 'access_permissions',
), '', true, true);

/* access controls */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'text' => 'resource_groups',
  'parent' => 'security',
  'action' => 'security/resourcegroup',
  'description' => 'resource_groups_desc',
  'icon' => '',
  'menuindex' => 2,
  'permissions' => 'access_permissions',
), '', true, true);

/* form customization */
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array (
  'text' => 'form_customization',
  'parent' => 'security',
  'action' => 'security/forms',
  'description' => 'form_customization_desc',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 3,
  'permissions' => 'customize_forms'
), '', true, true);

/* flush permissions */
$children[4]= $xpdo->newObject('modMenu');
$children[4]->fromArray(array (
  'text' => 'flush_access',
  'parent' => 'security',
  'action' => '',
  'description' => 'flush_access_desc',
  'icon' => 'images/icons/unzip.gif',
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
  'parent' => 'security',
  'action' => '',
  'description' => 'flush_sessions_desc',
  'icon' => 'images/icons/unzip.gif',
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


/* ***************** TOOLS MENU ***************** */
$menus[4]= $xpdo->newObject('modMenu');
$menus[4]->fromArray(array (
  'text' => 'tools',
  'parent' => '',
  'action' => '',
  'description' => '',
  'icon' => 'images/icons/menu_settings.gif',
  'menuindex' => 4,
  'permissions' => 'menu_tools',
), '', true, true);
$children = array();

/* import resources */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'import_resources',
  'parent' => 'tools',
  'action' => 'system/import',
  'description' => 'import_resources_desc',
  'icon' => 'images/icons/application_side_contract.png',
  'menuindex' => 0,
  'permissions' => 'import_static',
), '', true, true);

/* import html */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'import_site',
  'parent' => 'tools',
  'action' => 'system/import/html',
  'description' => 'import_site_desc',
  'icon' => 'images/icons/application_side_contract.png',
  'menuindex' => 1,
  'permissions' => 'import_static',
), '', true, true);

/* property sets */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array(
  'text' => 'propertysets',
  'parent' => 'tools',
  'action' => 'element/propertyset',
  'description' => 'propertysets_desc',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 2,
  'permissions' => 'property_sets',
), '', true, true);

/* media sources */
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array(
  'text' => 'sources',
  'parent' => 'tools',
  'action' => 'source',
  'description' => 'sources_desc',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 2,
  'permissions' => 'sources',
), '', true, true);

$menus[4]->addMany($children,'Children');
unset($children);

/* ***************** REPORTS MENU ***************** */
$menus[5]= $xpdo->newObject('modMenu');
$menus[5]->fromArray(array(
  'text' => 'reports',
  'parent' => '',
  'action' => '',
  'description' => '',
  'icon' => 'images/icons/menu_settings16.gif',
  'menuindex' => 5,
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
  'icon' => 'images/icons/cal.gif',
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
  'icon' => 'images/icons/comment.gif',
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
  'icon' => 'images/icons/logging.gif',
  'menuindex' => 3,
  'permissions' => 'view_sysinfo',
), '', true, true);

/* about */
$children[4]= $xpdo->newObject('modMenu');
$children[4]->fromArray(array (
  'text' => 'about',
  'parent' => 'reports',
  'action' => 'help',
  'description' => 'about_desc',
  'icon' => 'images/icons/information.png',
  'menuindex' => 4,
  'permissions' => 'about',
), '', true, true);

$menus[5]->addMany($children,'Children');
unset($children);

/* ***************** SYSTEM MENU ***************** */
$menus[6]= $xpdo->newObject('modMenu');
$menus[6]->fromArray(array (
  'text' => 'system',
  'parent' => '',
  'action' => '',
  'description' => '',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 6,
  'permissions' => 'menu_system',
), '', true, true);
$children = array();

/* package management */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'manage_workspaces',
  'parent' => 'system',
  'action' => 'workspaces',
  'description' => 'manage_workspaces_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 0,
  'permissions' => 'packages',
), '', true, true);
        
/* system settings */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'system_settings',
  'parent' => 'system',
  'action' => 'system/settings',
  'description' => 'system_settings_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 1,
  'permissions' => 'settings',
), '', true, true);

/* lexicon management */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'text' => 'lexicon_management',
  'parent' => 'system',
  'action' => 'workspaces/lexicon',
  'description' => 'lexicon_management_desc',
  'icon' => 'images/icons/logging.gif',
  'menuindex' => 2,
  'permissions' => 'lexicons',
), '', true, true);

/* content types */
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array (
  'text' => 'content_types',
  'parent' => 'system',
  'action' => 'system/contenttype',
  'description' => 'content_types_desc',
  'icon' => 'images/icons/logging.gif',
  'menuindex' => 3,
  'permissions' => 'content_types',
), '', true, true);
        
/* contexts */
$children[4]= $xpdo->newObject('modMenu');
$children[4]->fromArray(array (
  'text' => 'contexts',
  'parent' => 'system',
  'action' => 'context',
  'description' => 'contexts_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 4,
  'permissions' => 'view_context',
), '', true, true);

/* menus and actions */
$children[5]= $xpdo->newObject('modMenu');
$children[5]->fromArray(array (
  'text' => 'edit_menu',
  'parent' => 'system',
  'action' => 'system/action',
  'description' => 'edit_menu_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 5,
  'permissions' => 'menus,actions',
), '', true, true);

/* namespaces */
$children[6]= $xpdo->newObject('modMenu');
$children[6]->fromArray(array (
  'text' => 'namespaces',
  'parent' => 'system',
  'action' => 'workspaces/namespace',
  'description' => 'namespaces_desc',
  'icon' => '',
  'menuindex' => 6,
  'permissions' => 'namespaces',
), '', true, true);

$menus[6]->addMany($children,'Children');
unset($children);

/* ***************** USER MENU ***************** */
$menus[7]= $xpdo->newObject('modMenu');
$menus[7]->fromArray(array (
  'text' => 'user',
  'parent' => '',
  'action' => '',
  'description' => '',
  'icon' => 'images/icons/user_go.png',
  'menuindex' => 7,
  'permissions' => 'menu_user',
), '', true, true);
$children = array();

/* profile */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'profile',
  'parent' => 'user',
  'action' => 'security/profile',
  'description' => 'profile_desc',
  'icon' => '',
  'menuindex' => 0,
  'permissions' => 'change_profile',
), '', true, true);

/* messages */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'messages',
  'parent' => 'user',
  'action' => 'security/message',
  'description' => 'messages_desc',
  'icon' => 'images/icons/messages.gif',
  'menuindex' => 1,
  'permissions' => 'messages',
), '', true, true);

$menus[7]->addMany($children,'Children');
unset($children);

/* ***************** SUPPORT MENU ***************** */
$menus[8]= $xpdo->newObject('modMenu');
$menus[8]->fromArray(array (
  'text' => 'support',
  'parent' => '',
  'action' => '',
  'description' => 'support_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 8,
  'permissions' => 'menu_support',
), '', true, true);
$children = array();

/* forums */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'forums',
  'parent' => 'support',
  'action' => '',
  'description' => 'forums_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 0,
  'handler' => 'window.open("http://modx.com/forums");',
), '', true, true);

/* confluence */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'wiki',
  'parent' => 'support',
  'action' => '',
  'description' => 'wiki_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 1,
  'handler' => 'window.open("http://rtfm.modx.com/");',
), '', true, true);

/* jira */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'text' => 'jira',
  'parent' => 'support',
  'action' => '',
  'description' => 'jira_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 2,
  'handler' => 'window.open("http://bugs.modx.com/projects/revo/issues");',
), '', true, true);

/* api docs */
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array (
  'text' => 'api_docs',
  'parent' => 'support',
  'action' => '',
  'description' => 'api_docs_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 3,
  'handler' => 'window.open("http://api.modx.com/revolution/2.2/");',
), '', true, true);

$menus[8]->addMany($children,'Children');
unset($children);

return $menus;