<?php
/**
 * Adds all top Menu items to build
 *
 * @package modx
 * @subpackage build
 */
$menus = array();

/* ***************** SITE MENU ***************** */
$menus[0]= $xpdo->newObject('modMenu');
$menus[0]->fromArray(array (
  'text' => 'site',
  'parent' => '',
  'action' => 0,
  'description' => '',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 0,
), '', true, true);

$children = array();

/* preview */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'preview',
  'parent' => 'site',
  'action' => 0,
  'description' => 'preview_desc',
  'icon' => 'images/icons/show.gif',
  'menuindex' => 0,
  'handler' => 'window.open("../");',
), '', true, true);


/* clear cache */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'refresh_site',
  'parent' => 'site',
  'action' => 0,
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
  'action' => 0,
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
  'action' => 45,
  'description' => 'search_desc',
  'icon' => 'images/icons/context_view.gif',
  'menuindex' => 3,
  'permissions' => 'search',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 45,
          'parent' => 0,
          'namespace' => 'core',
          'controller' => 'search',
          'haslayout' => 1,
          'lang_topics' => '',
          'assets' => '',
        ), '', true, true);
        $children[3]->addOne($action);


/* resource pages */
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 43,
          'parent' => 36,
          'namespace' => 'core',
          'controller' => 'resource/create',
          'haslayout' => 1,
          'lang_topics' => 'resource',
          'assets' => '',
          'help_url' => 'http://docs.modxcms.com/display/revolution/Resource',
        ), '', true, true);

/* new document resource */
$children[4]= $xpdo->newObject('modMenu');
$children[4]->fromArray(array (
  'text' => 'new_document',
  'parent' => 'site',
  'action' => 44,
  'description' => 'new_document_desc',
  'icon' => 'images/icons/folder_page_add.png',
  'menuindex' => 4,
  'permissions' => 'new_document',
), '', true, true);
$children[4]->addOne($action);

/* new weblink resource */
$children[5]= $xpdo->newObject('modMenu');
$children[5]->fromArray(array (
  'text' => 'new_weblink',
  'parent' => 'site',
  'action' => 44,
  'description' => 'new_weblink_desc',
  'icon' => 'images/icons/link_add.png',
  'menuindex' => 5,
  'params' => '&class_key=modWebLink',
  'permissions' => 'new_document',
), '', true, true);
$children[5]->addOne($action);

/* new symlink resource */
$children[6]= $xpdo->newObject('modMenu');
$children[6]->fromArray(array (
  'text' => 'new_symlink',
  'parent' => 'site',
  'action' => 44,
  'description' => 'new_symlink_desc',
  'icon' => 'images/icons/link_add.png',
  'menuindex' => 6,
  'params' => '&class_key=modSymLink',
  'permissions' => 'new_document',
), '', true, true);
$children[6]->addOne($action);

/* new static resource */
$children[7]= $xpdo->newObject('modMenu');
$children[7]->fromArray(array (
  'text' => 'new_static_resource',
  'parent' => 'site',
  'action' => 44,
  'description' => 'new_static_resource_desc',
  'icon' => 'images/icons/link_add.png',
  'menuindex' => 7,
  'params' => '&class_key=modStaticResource',
  'permissions' => 'new_document',
), '', true, true);
$children[7]->addOne($action);
unset($action);

/* logout */
$children[8]= $xpdo->newObject('modMenu');
$children[8]->fromArray(array (
  'text' => 'logout',
  'parent' => 'site',
  'action' => 0,
  'description' => 'logout_desc',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 8,
  'handler' => 'MODx.logout(); return false;',
), '', true, true);


$menus[0]->addMany($children,'Children');
unset($children);





/* ***************** COMPONENTS MENU ***************** */
$menus[1]= $xpdo->newObject('modMenu');
$menus[1]->fromArray(array (
  'text' => 'components',
  'parent' => '',
  'action' => 0,
  'description' => '',
  'icon' => 'images/icons/plugin.gif',
  'menuindex' => 1,
), '', true, true);


/* ****************** SECURITY MENU ****************** */
$menus[2]= $xpdo->newObject('modMenu');
$menus[2]->fromArray(array (
  'text' => 'security',
  'parent' => '',
  'action' => 0,
  'description' => '',
  'icon' => 'images/icons/lock.gif',
  'menuindex' => 2,
  'permissions' => 'access_permissions',
), '', true, true);
$children = array();

/* user management */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'user_management',
  'parent' => 'security',
  'action' => 53,
  'description' => 'user_management_desc',
  'icon' => 'images/icons/user.gif',
  'menuindex' => 0,
  'permissions' => 'view_user',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 53,
          'namespace' => 'core',
          'parent' => 46,
          'controller' => 'security/user',
          'haslayout' => 1,
          'lang_topics' => 'user',
          'assets' => '',
          'help_url' => 'http://docs.modxcms.com/display/revolution/Users',
        ), '', true, true);
        $children[0]->addOne($action);

/* user group management */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'user_group_management',
  'parent' => 'security',
  'action' => 66,
  'description' => 'user_group_management_desc',
  'icon' => 'images/icons/mnu_users.gif',
  'menuindex' => 1,
  'permissions' => 'access_permissions',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 66,
          'namespace' => 'core',
          'parent' => 46,
          'controller' => 'security/permission',
          'haslayout' => 1,
          'lang_topics' => 'user,access,policy',
          'assets' => '',
          'help_url' => 'http://docs.modxcms.com/display/revolution/Security',
        ), '', true, true);
        $children[1]->addOne($action);

/* access controls */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'text' => 'resource_groups',
  'parent' => 'security',
  'action' => 37,
  'description' => 'resource_groups_desc',
  'icon' => '',
  'menuindex' => 2,
  'permissions' => 'access_permissions',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 37,
          'namespace' => 'core',
          'parent' => 46,
          'controller' => 'security/resourcegroup/index',
          'haslayout' => 1,
          'lang_topics' => 'resource,user,access',
          'assets' => '',
          'help_url' => 'http://docs.modxcms.com/display/revolution/Resource+Groups',
        ), '', true, true);
        $children[2]->addOne($action);

/* form customization */
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array (
  'text' => 'form_customization',
  'parent' => 'security',
  'action' => 83,
  'description' => 'form_customization_desc',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 3,
  'permissions' => 'customize_forms'
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 83,
          'namespace' => 'core',
          'parent' => 46,
          'controller' => 'security/forms',
          'haslayout' => 1,
          'lang_topics' => 'formcustomization,user,access,policy',
          'assets' => '',
          'help_url' => 'http://docs.modxcms.com/display/revolution/Form+Customization',
        ), '', true, true);
        $children[3]->addOne($action);

/* flush permissions */
$children[4]= $xpdo->newObject('modMenu');
$children[4]->fromArray(array (
  'text' => 'flush_access',
  'parent' => 'security',
  'action' => 0,
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
  'action' => 0,
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

$menus[2]->addMany($children,'Children');
unset($children);


/* ***************** TOOLS MENU ***************** */
$menus[3]= $xpdo->newObject('modMenu');
$menus[3]->fromArray(array (
  'text' => 'tools',
  'parent' => '',
  'action' => 0,
  'description' => '',
  'icon' => 'images/icons/menu_settings.gif',
  'menuindex' => 3,
), '', true, true);
$children = array();

/* import resources */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'import_resources',
  'parent' => 'tools',
  'action' => 59,
  'description' => 'import_resources_desc',
  'icon' => 'images/icons/application_side_contract.png',
  'menuindex' => 0,
  'permissions' => 'import_static',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 59,
          'namespace' => 'core',
          'parent' => 3,
          'controller' => 'system/import',
          'haslayout' => 1,
          'lang_topics' => 'import',
          'assets' => '',
        ), '', true, true);
        $children[0]->addOne($action);

/* import html */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'import_site',
  'parent' => 'tools',
  'action' => 60,
  'description' => 'import_site_desc',
  'icon' => 'images/icons/application_side_contract.png',
  'menuindex' => 1,
  'permissions' => 'import_static',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 60,
          'namespace' => 'core',
          'parent' => 59,
          'controller' => 'system/import/html',
          'haslayout' => 1,
          'lang_topics' => 'import',
          'assets' => '',
        ), '', true, true);
        $children[1]->addOne($action);

/* property sets */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array(
  'text' => 'propertysets',
  'parent' => 'tools',
  'action' => 82,
  'description' => 'propertysets_desc',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 2,
  'permissions' => 'property_sets',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 82,
          'namespace' => 'core',
          'parent' => 10,
          'controller' => 'element/propertyset/index',
          'haslayout' => 1,
          'lang_topics' => 'element,category,propertyset',
          'assets' => '',
          'help_url' => 'http://docs.modxcms.com/display/revolution/Properties+and+Property+Sets',
        ), '', true, true);
        $children[2]->addOne($action);

$menus[3]->addMany($children,'Children');
unset($children);

/* ***************** REPORTS MENU ***************** */
$menus[4]= $xpdo->newObject('modMenu');
$menus[4]->fromArray(array(
  'text' => 'reports',
  'parent' => '',
  'action' => 0,
  'description' => '',
  'icon' => 'images/icons/menu_settings16.gif',
  'menuindex' => 4,
), '', true, true);
$children = array();

/* site schedule */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'site_schedule',
  'parent' => 'reports',
  'action' => 42,
  'description' => 'site_schedule_desc',
  'icon' => 'images/icons/cal.gif',
  'menuindex' => 0,
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 42,
          'namespace' => 'core',
          'parent' => 36,
          'controller' => 'resource/site_schedule',
          'haslayout' => 1,
          'lang_topics' => 'resource',
          'assets' => '',
        ), '', true, true);
        $children[0]->addOne($action);

/* manager actions */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'view_logging',
  'parent' => 'reports',
  'action' => 14,
  'description' => 'view_logging_desc',
  'icon' => '',
  'menuindex' => 1,
  'permissions' => 'logs',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 14,
          'namespace' => 'core',
          'parent' => '',
          'controller' => 'system/logs/index',
          'haslayout' => 1,
          'lang_topics' => 'manager_log',
          'assets' => '',
        ), '', true, true);
        $children[1]->addOne($action);

/* error log */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'text' => 'eventlog_viewer',
  'parent' => 'reports',
  'action' => 57,
  'description' => 'eventlog_viewer_desc',
  'icon' => 'images/icons/comment.gif',
  'menuindex' => 2,
  'permissions' => 'view_eventlog',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 57,
          'namespace' => 'core',
          'parent' => 3,
          'controller' => 'system/event',
          'haslayout' => 1,
          'lang_topics' => 'system_events',
          'assets' => '',
        ), '', true, true);
        $children[2]->addOne($action);

/* system info */
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array (
  'text' => 'view_sysinfo',
  'parent' => 'reports',
  'action' => 4,
  'description' => 'view_sysinfo_desc',
  'icon' => 'images/icons/logging.gif',
  'menuindex' => 3,
  'permissions' => 'view_sysinfo',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 4,
          'namespace' => 'core',
          'parent' => 3,
          'controller' => 'system/info',
          'haslayout' => 1,
          'lang_topics' => 'system_info',
          'assets' => '',
        ), '', true, true);
        $children[3]->addOne($action);

/* about */
$children[4]= $xpdo->newObject('modMenu');
$children[4]->fromArray(array (
  'text' => 'about',
  'parent' => 'reports',
  'action' => 63,
  'description' => 'about_desc',
  'icon' => 'images/icons/information.png',
  'menuindex' => 4,
  'permissions' => 'about',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 63,
          'namespace' => 'core',
          'parent' => '',
          'controller' => 'help',
          'haslayout' => 1,
          'lang_topics' => 'about',
          'assets' => '',
        ), '', true, true);
        $children[4]->addOne($action);

$menus[4]->addMany($children,'Children');
unset($children);

/* ***************** SYSTEM MENU ***************** */
$menus[5]= $xpdo->newObject('modMenu');
$menus[5]->fromArray(array (
  'text' => 'system',
  'parent' => '',
  'action' => 0,
  'description' => '',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 5,
), '', true, true);
$children = array();

/* contexts */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'contexts',
  'parent' => 'system',
  'action' => 6,
  'description' => 'contexts_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 0,
  'permissions' => 'view_context',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 6,
          'namespace' => 'core',
          'parent' => '',
          'controller' => 'context',
          'haslayout' => 1,
          'lang_topics' => 'context',
          'assets' => '',
          'help_url' => 'http://docs.modxcms.com/display/revolution/Contexts',
        ), '', true, true);
        $children[0]->addOne($action);

/* menus and actions */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'edit_menu',
  'parent' => 'system',
  'action' => 2,
  'description' => 'edit_menu_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 1,
  'permissions' => 'menus,actions',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 2,
          'namespace' => 'core',
          'parent' => 3,
          'controller' => 'system/action',
          'haslayout' => 1,
          'lang_topics' => 'action,menu,namespace',
          'assets' => '',
          'help_url' => 'http://docs.modxcms.com/display/revolution/Actions+and+Menus',
        ), '', true, true);
        $children[1]->addOne($action);

/* package management */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'text' => 'manage_workspaces',
  'parent' => 'system',
  'action' => 68,
  'description' => 'manage_workspaces_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 2,
  'permissions' => 'packages',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 68,
          'namespace' => 'core',
          'parent' => 3,
          'controller' => 'workspaces',
          'haslayout' => 1,
          'lang_topics' => 'workspace',
          'assets' => '',
          'help_url' => 'http://docs.modxcms.com/display/revolution/Package+Management',
        ), '', true, true);
        $children[2]->addOne($action);

/* lexicon management */
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array (
  'text' => 'lexicon_management',
  'parent' => 'system',
  'action' => 73,
  'description' => 'lexicon_management_desc',
  'icon' => 'images/icons/logging.gif',
  'menuindex' => 3,
  'permissions' => 'lexicons',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 73,
          'namespace' => 'core',
          'parent' => 68,
          'controller' => 'workspaces/lexicon',
          'haslayout' => 1,
          'lang_topics' => 'package_builder,lexicon,namespace',
          'assets' => '',
          'help_url' => 'http://docs.modxcms.com/display/revolution/Internationalization',
        ), '', true, true);
        $children[3]->addOne($action);

/* content types */
$children[4]= $xpdo->newObject('modMenu');
$children[4]->fromArray(array (
  'text' => 'content_types',
  'parent' => 'system',
  'action' => 69,
  'description' => 'content_types_desc',
  'icon' => 'images/icons/logging.gif',
  'menuindex' => 4,
  'permissions' => 'content_types',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 69,
          'namespace' => 'core',
          'parent' => 3,
          'controller' => 'system/contenttype',
          'haslayout' => 1,
          'lang_topics' => 'content_type',
          'assets' => '',
          'help_url' => 'http://docs.modxcms.com/display/revolution/Content+Types',
        ), '', true, true);
        $children[4]->addOne($action);

/* namespaces */
$children[5]= $xpdo->newObject('modMenu');
$children[5]->fromArray(array (
  'text' => 'namespaces',
  'parent' => 'system',
  'action' => 74,
  'description' => 'namespaces_desc',
  'icon' => '',
  'menuindex' => 5,
  'permissions' => 'namespaces',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 48,
          'namespace' => 'core',
          'parent' => 68,
          'controller' => 'workspaces/namespace',
          'haslayout' => 1,
          'lang_topics' => 'workspace,package_builder,lexicon,namespace',
          'assets' => '',
          'help_url' => 'http://docs.modxcms.com/display/revolution/Namespaces',
        ), '', true, true);
        $children[5]->addOne($action);

/* system settings */
$children[6]= $xpdo->newObject('modMenu');
$children[6]->fromArray(array (
  'text' => 'system_settings',
  'parent' => 'system',
  'action' => 61,
  'description' => 'system_settings_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 6,
  'permissions' => 'settings',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 30,
          'namespace' => 'core',
          'parent' => 3,
          'controller' => 'system/settings',
          'haslayout' => 1,
          'lang_topics' => 'setting',
          'assets' => '',
          'help_url' => 'http://docs.modxcms.com/display/revolution/Settings',
        ), '', true, true);
        $children[6]->addOne($action);

$menus[5]->addMany($children,'Children');
unset($children);

/* ***************** USER MENU ***************** */
$menus[6]= $xpdo->newObject('modMenu');
$menus[6]->fromArray(array (
  'text' => 'user',
  'parent' => '',
  'action' => 0,
  'description' => '',
  'icon' => 'images/icons/user_go.png',
  'menuindex' => 6,
), '', true, true);
$children = array();

/* profile */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'profile',
  'parent' => 'user',
  'action' => 49,
  'description' => 'profile_desc',
  'icon' => '',
  'menuindex' => 0,
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 36,
          'namespace' => 'core',
          'parent' => 46,
          'controller' => 'security/profile',
          'haslayout' => 1,
          'lang_topics' => 'user',
          'assets' => '',
        ), '', true, true);
        $children[0]->addOne($action);

/* messages */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'messages',
  'parent' => 'user',
  'action' => 47,
  'description' => 'messages_desc',
  'icon' => 'images/icons/messages.gif',
  'menuindex' => 1,
  'permissions' => 'messages',
), '', true, true);
        $action= $xpdo->newObject('modAction');
        $action->fromArray(array (
          'id' => 37,
          'namespace' => 'core',
          'parent' => 46,
          'controller' => 'security/message',
          'haslayout' => 1,
          'lang_topics' => 'messages',
          'assets' => '',
        ), '', true, true);
        $children[1]->addOne($action);

$menus[6]->addMany($children,'Children');
unset($children);

/* ***************** SUPPORT MENU ***************** */
$menus[7]= $xpdo->newObject('modMenu');
$menus[7]->fromArray(array (
  'text' => 'support',
  'parent' => '',
  'action' => 0,
  'description' => 'support_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 7,
), '', true, true);
$children = array();

/* forums */
$children[0]= $xpdo->newObject('modMenu');
$children[0]->fromArray(array (
  'text' => 'forums',
  'parent' => 'support',
  'action' => 0,
  'description' => 'forums_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 0,
  'handler' => 'window.open("http://www.modxcms.com/forums");',
), '', true, true);

/* confluence */
$children[1]= $xpdo->newObject('modMenu');
$children[1]->fromArray(array (
  'text' => 'wiki',
  'parent' => 'support',
  'action' => 0,
  'description' => 'wiki_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 1,
  'handler' => 'window.open("http://docs.modxcms.com/");',
), '', true, true);

/* jira */
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'text' => 'jira',
  'parent' => 'support',
  'action' => 0,
  'description' => 'jira_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 2,
  'handler' => 'window.open("http://svn.modxcms.com/jira/browse/MODX");',
), '', true, true);

/* api docs */
$children[3]= $xpdo->newObject('modMenu');
$children[3]->fromArray(array (
  'text' => 'api_docs',
  'parent' => 'support',
  'action' => 0,
  'description' => 'api_docs_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 3,
  'handler' => 'window.open("http://api.modxcms.com/");',
), '', true, true);

$menus[7]->addMany($children,'Children');
unset($children);


/* export site */
/*
$children[2]= $xpdo->newObject('modMenu');
$children[2]->fromArray(array (
  'text' => 'export_site',
  'parent' => 'tools',
  'action' => 1,
  'description' => 'export_site_desc',
  'icon' => 'images/icons/application_side_expand.png',
  'menuindex' => 2,
), '', true, true);
*/


return $menus;