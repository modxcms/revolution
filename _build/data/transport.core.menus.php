<?php
$collection['1']= $xpdo->newObject('modMenu');
$collection['1']->fromArray(array (
  'id' => 1,
  'parent' => 0,
  'action' => 0,
  'text' => 'site',
  'description' => '',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 0,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['2']= $xpdo->newObject('modMenu');
$collection['2']->fromArray(array (
  'id' => 2,
  'parent' => 0,
  'action' => 0,
  'text' => 'components',
  'description' => '',
  'icon' => 'images/icons/plugin.gif',
  'menuindex' => 1,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['4']= $xpdo->newObject('modMenu');
$collection['4']->fromArray(array (
  'id' => 4,
  'parent' => 1,
  'action' => 0,
  'text' => 'preview',
  'description' => 'preview_desc',
  'icon' => 'images/icons/show.gif',
  'menuindex' => 0,
  'params' => '',
  'handler' => 'window.open("../");',
), '', true, true);
$collection['5']= $xpdo->newObject('modMenu');
$collection['5']->fromArray(array (
  'id' => 5,
  'parent' => 1,
  'action' => 0,
  'text' => 'refresh_site',
  'description' => 'refresh_site_desc',
  'icon' => 'images/icons/refresh.png',
  'menuindex' => 1,
  'params' => '',
  'handler' => 'MODx.clearCache(); return false;',
), '', true, true);
$collection['6']= $xpdo->newObject('modMenu');
$collection['6']->fromArray(array (
  'id' => 6,
  'parent' => 1,
  'action' => 45,
  'text' => 'search',
  'description' => 'search_desc',
  'icon' => 'images/icons/context_view.gif',
  'menuindex' => 3,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['8']= $xpdo->newObject('modMenu');
$collection['8']->fromArray(array (
  'id' => 8,
  'parent' => 0,
  'action' => 0,
  'text' => 'security',
  'description' => '',
  'icon' => 'images/icons/lock.gif',
  'menuindex' => 2,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['9']= $xpdo->newObject('modMenu');
$collection['9']->fromArray(array (
  'id' => 9,
  'parent' => 0,
  'action' => 0,
  'text' => 'tools',
  'description' => '',
  'icon' => 'images/icons/menu_settings.gif',
  'menuindex' => 3,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['10']= $xpdo->newObject('modMenu');
$collection['10']->fromArray(array (
  'id' => 10,
  'parent' => 0,
  'action' => 0,
  'text' => 'reports',
  'description' => '',
  'icon' => 'images/icons/menu_settings16.gif',
  'menuindex' => 4,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['11']= $xpdo->newObject('modMenu');
$collection['11']->fromArray(array (
  'id' => 11,
  'parent' => 0,
  'action' => 0,
  'text' => 'user',
  'description' => '',
  'icon' => 'images/icons/user_go.png',
  'menuindex' => 6,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['12']= $xpdo->newObject('modMenu');
$collection['12']->fromArray(array (
  'id' => 12,
  'parent' => 1,
  'action' => 44,
  'text' => 'new_document',
  'description' => '',
  'icon' => 'images/icons/folder_page_add.png',
  'menuindex' => 4,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['13']= $xpdo->newObject('modMenu');
$collection['13']->fromArray(array (
  'id' => 13,
  'parent' => 1,
  'action' => 44,
  'text' => 'new_weblink',
  'description' => '',
  'icon' => 'images/icons/link_add.png',
  'menuindex' => 5,
  'params' => '&class_key=modWebLink',
  'handler' => '',
), '', true, true);
$collection['14']= $xpdo->newObject('modMenu');
$collection['14']->fromArray(array (
  'id' => 14,
  'parent' => 8,
  'action' => 37,
  'text' => 'resource_groups',
  'description' => 'resource_groups_desc',
  'icon' => '',
  'menuindex' => 2,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['16']= $xpdo->newObject('modMenu');
$collection['16']->fromArray(array (
  'id' => 16,
  'parent' => 8,
  'action' => 0,
  'text' => 'flush_sessions',
  'description' => 'flush_sessions_desc',
  'icon' => 'images/icons/unzip.gif',
  'menuindex' => 6,
  'params' => '',
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
), '', true, true);
$collection['18']= $xpdo->newObject('modMenu');
$collection['18']->fromArray(array (
  'id' => 18,
  'parent' => 8,
  'action' => 53,
  'text' => 'user_management',
  'description' => 'user_management_desc',
  'icon' => 'images/icons/user.gif',
  'menuindex' => 0,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['19']= $xpdo->newObject('modMenu');
$collection['19']->fromArray(array (
  'id' => 19,
  'parent' => 8,
  'action' => 66,
  'text' => 'user_group_management',
  'description' => 'user_group_management_desc',
  'icon' => 'images/icons/mnu_users.gif',
  'menuindex' => 1,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['20']= $xpdo->newObject('modMenu');
$collection['20']->fromArray(array (
  'id' => 20,
  'parent' => 8,
  'action' => 48,
  'text' => 'access_permissions',
  'description' => 'access_permissions_desc',
  'icon' => 'images/icons/password.gif',
  'menuindex' => 3,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['21']= $xpdo->newObject('modMenu');
$collection['21']->fromArray(array (
  'id' => 21,
  'parent' => 8,
  'action' => 0,
  'text' => 'flush_access',
  'description' => 'flush_access_desc',
  'icon' => 'images/icons/unzip.gif',
  'menuindex' => 5,
  'params' => '',
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
), '', true, true);
$collection['22']= $xpdo->newObject('modMenu');
$collection['22']->fromArray(array (
  'id' => 22,
  'parent' => 1,
  'action' => 0,
  'text' => 'remove_locks',
  'description' => 'remove_locks_desc',
  'icon' => 'images/ext/default/grid/hmenu-unlock.png',
  'menuindex' => 2,
  'params' => '',
  'handler' => '
            MODx.msg.confirm({
                title: _(\'remove_locks\')
                ,text: _(\'confirm_remove_locks\')
                ,url: MODx.config.connectors_url+\'system/remove_locks.php\'
                ,params: {
                    action: \'remove\'
                }
                ,listeners: {
                    \'success\': {fn:function() { Ext.getCmp("modx_resource_tree").refresh(); },scope:this}
                }
            });',
), '', true, true);
$collection['24']= $xpdo->newObject('modMenu');
$collection['24']->fromArray(array (
  'id' => 24,
  'parent' => 9,
  'action' => 59,
  'text' => 'import_resources',
  'description' => 'import_resources_desc',
  'icon' => 'images/icons/application_side_contract.png',
  'menuindex' => 0,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['25']= $xpdo->newObject('modMenu');
$collection['25']->fromArray(array (
  'id' => 25,
  'parent' => 9,
  'action' => 60,
  'text' => 'import_site',
  'description' => 'import_site_desc',
  'icon' => 'images/icons/application_side_contract.png',
  'menuindex' => 1,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['26']= $xpdo->newObject('modMenu');
$collection['26']->fromArray(array (
  'id' => 26,
  'parent' => 9,
  'action' => 1,
  'text' => 'export_site',
  'description' => 'export_site_desc',
  'icon' => 'images/icons/application_side_expand.png',
  'menuindex' => 2,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['28']= $xpdo->newObject('modMenu');
$collection['28']->fromArray(array (
  'id' => 28,
  'parent' => 55,
  'action' => 6,
  'text' => 'contexts',
  'description' => 'contexts_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 0,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['29']= $xpdo->newObject('modMenu');
$collection['29']->fromArray(array (
  'id' => 29,
  'parent' => 55,
  'action' => 68,
  'text' => 'manage_workspaces',
  'description' => 'manage_workspaces_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 2,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['30']= $xpdo->newObject('modMenu');
$collection['30']->fromArray(array (
  'id' => 30,
  'parent' => 55,
  'action' => 61,
  'text' => 'system_settings',
  'description' => 'system_settings_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 6,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['31']= $xpdo->newObject('modMenu');
$collection['31']->fromArray(array (
  'id' => 31,
  'parent' => 10,
  'action' => 42,
  'text' => 'site_schedule',
  'description' => 'site_schedule_desc',
  'icon' => 'images/icons/cal.gif',
  'menuindex' => 0,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['32']= $xpdo->newObject('modMenu');
$collection['32']->fromArray(array (
  'id' => 32,
  'parent' => 10,
  'action' => 14,
  'text' => 'view_logging',
  'description' => 'view_logging_desc',
  'icon' => '',
  'menuindex' => 1,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['33']= $xpdo->newObject('modMenu');
$collection['33']->fromArray(array (
  'id' => 33,
  'parent' => 10,
  'action' => 57,
  'text' => 'eventlog_viewer',
  'description' => 'eventlog_viewer_desc',
  'icon' => 'images/icons/comment.gif',
  'menuindex' => 2,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['34']= $xpdo->newObject('modMenu');
$collection['34']->fromArray(array (
  'id' => 34,
  'parent' => 10,
  'action' => 4,
  'text' => 'view_sysinfo',
  'description' => 'view_sysinfo_desc',
  'icon' => 'images/icons/logging.gif',
  'menuindex' => 3,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['35']= $xpdo->newObject('modMenu');
$collection['35']->fromArray(array (
  'id' => 35,
  'parent' => 10,
  'action' => 63,
  'text' => 'about',
  'description' => 'about_desc',
  'icon' => 'images/icons/information.png',
  'menuindex' => 4,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['36']= $xpdo->newObject('modMenu');
$collection['36']->fromArray(array (
  'id' => 36,
  'parent' => 11,
  'action' => 49,
  'text' => 'profile',
  'description' => 'profile_desc',
  'icon' => '',
  'menuindex' => 0,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['37']= $xpdo->newObject('modMenu');
$collection['37']->fromArray(array (
  'id' => 37,
  'parent' => 11,
  'action' => 47,
  'text' => 'messages',
  'description' => 'messages_desc',
  'icon' => 'images/icons/messages.gif',
  'menuindex' => 1,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['38']= $xpdo->newObject('modMenu');
$collection['38']->fromArray(array (
  'id' => 38,
  'parent' => 55,
  'action' => 2,
  'text' => 'edit_menu',
  'description' => 'edit_menu_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 1,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['39']= $xpdo->newObject('modMenu');
$collection['39']->fromArray(array (
  'id' => 39,
  'parent' => 0,
  'action' => 0,
  'text' => 'support',
  'description' => 'support_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 7,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['40']= $xpdo->newObject('modMenu');
$collection['40']->fromArray(array (
  'id' => 40,
  'parent' => 39,
  'action' => 0,
  'text' => 'forums',
  'description' => 'forums_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 0,
  'params' => '',
  'handler' => 'window.open("http://www.modxcms.com/forums");',
), '', true, true);
$collection['41']= $xpdo->newObject('modMenu');
$collection['41']->fromArray(array (
  'id' => 41,
  'parent' => 39,
  'action' => 0,
  'text' => 'wiki',
  'description' => 'wiki_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 1,
  'params' => '',
  'handler' => 'window.open("http://svn.modxcms.com/docs/");',
), '', true, true);
$collection['42']= $xpdo->newObject('modMenu');
$collection['42']->fromArray(array (
  'id' => 42,
  'parent' => 39,
  'action' => 0,
  'text' => 'jira',
  'description' => 'jira_desc',
  'icon' => 'images/icons/sysinfo.gif',
  'menuindex' => 2,
  'params' => '',
  'handler' => 'window.open("http://svn.modxcms.com/jira/browse/MODX");',
), '', true, true);
$collection['43']= $xpdo->newObject('modMenu');
$collection['43']->fromArray(array (
  'id' => 43,
  'parent' => 8,
  'action' => 65,
  'text' => 'policy_management',
  'description' => 'policy_management_desc',
  'icon' => 'images/icons/logging.gif',
  'menuindex' => 4,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['44']= $xpdo->newObject('modMenu');
$collection['44']->fromArray(array (
  'id' => 44,
  'parent' => 55,
  'action' => 69,
  'text' => 'content_types',
  'description' => 'content_types_desc',
  'icon' => 'images/icons/logging.gif',
  'menuindex' => 4,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['46']= $xpdo->newObject('modMenu');
$collection['46']->fromArray(array (
  'id' => 46,
  'parent' => 55,
  'action' => 73,
  'text' => 'lexicon_management',
  'description' => 'lexicon_management_desc',
  'icon' => 'images/icons/logging.gif',
  'menuindex' => 3,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['48']= $xpdo->newObject('modMenu');
$collection['48']->fromArray(array (
  'id' => 48,
  'parent' => 55,
  'action' => 74,
  'text' => 'namespaces',
  'description' => 'namespaces_desc',
  'icon' => '',
  'menuindex' => 5,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['50']= $xpdo->newObject('modMenu');
$collection['50']->fromArray(array (
  'id' => 50,
  'parent' => 1,
  'action' => 44,
  'text' => 'new_symlink',
  'description' => '',
  'icon' => 'images/icons/link_add.png',
  'menuindex' => 6,
  'params' => '&class_key=modSymLink',
  'handler' => '',
), '', true, true);
$collection['51']= $xpdo->newObject('modMenu');
$collection['51']->fromArray(array (
  'id' => 51,
  'parent' => 1,
  'action' => 44,
  'text' => 'new_static_resource',
  'description' => '',
  'icon' => 'images/icons/link_add.png',
  'menuindex' => 7,
  'params' => '&class_key=modStaticResource',
  'handler' => '',
), '', true, true);
$collection['55']= $xpdo->newObject('modMenu');
$collection['55']->fromArray(array (
  'id' => 55,
  'parent' => 0,
  'action' => 0,
  'text' => 'system',
  'description' => '',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 5,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['56']= $xpdo->newObject('modMenu');
$collection['56']->fromArray(array (
  'id' => 56,
  'parent' => 9,
  'action' => 82,
  'text' => 'propertysets',
  'description' => 'propertysets_desc',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 3,
  'params' => '',
  'handler' => '',
), '', true, true);
$collection['57']= $xpdo->newObject('modMenu');
$collection['57']->fromArray(array (
  'id' => 57,
  'parent' => 1,
  'action' => 0,
  'text' => 'logout',
  'description' => '',
  'icon' => 'images/misc/logo_tbar.gif',
  'menuindex' => 8,
  'params' => '',
  'handler' => 'MODx.logout(); return false;',
), '', true, true);
