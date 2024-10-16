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

$menusConfig = [
    // region: Main Navigation
    [
        'text' => 'topnav',
        'description' => 'topnav_desc',
        'permissions' => '',
        'action' => '',
        'children' => [
            // region: Content
            [
                'text' => 'site',
                'description' => '',
                'permissions' => 'menu_site',
                'action' => '',
                'icon' => '<i class="icon-file-text-o icon"></i>',
                'children' => [
                    // region: New Resource
                    [
                        'text' => 'new_resource',
                        'description' => 'new_resource_desc',
                        'permissions' => 'new_document',
                        'action' => 'resource/create',
                    ],
                    // endregion
                    // region: Clear Cache
                    [
                        'text' => 'refresh_site',
                        'description' => 'refresh_site_desc',
                        'permissions' => 'empty_cache',
                        'action' => '',
                        'handler' => 'MODx.clearCache(); return false;',
                        'children' => [
                            // region: Refresh URIs
                            [
                                'text' => 'refreshuris',
                                'description' => 'refreshuris_desc',
                                'permissions' => 'empty_cache',
                                'action' => '',
                                'handler' => 'MODx.refreshURIs(); return false;',
                            ]
                            // endregion
                        ]
                    ],
                    // endregion
                    // region: Remove Locks
                    [
                        'text' => 'remove_locks',
                        'description' => 'remove_locks_desc',
                        'permissions' => 'remove_locks',
                        'action' => '',
                        'handler' => 'MODx.removeLocks();return false;',
                    ],
                    // endregion
                    // region: Site Schedule
                    [
                        'text' => 'site_schedule',
                        'description' => 'site_schedule_desc',
                        'permissions' => 'view_document',
                        'action' => 'resource/site_schedule',
                    ],
                    // endregion
                    // region: Content Types
                    [
                        'text' => 'content_types',
                        'description' => 'content_types_desc',
                        'permissions' => 'content_types',
                        'action' => 'system/contenttype',
                    ],
                    // endregion
                    // region: Trash
                    [
                        'text' => 'trash',
                        'description' => 'trash_desc',
                        'permissions' => 'menu_trash',
                        'action' => 'resource/trash',
                    ],
                    // endregion
                ],
            ],
            // endregion
            // region: Media
            [
                'text' => 'media',
                'description' => '',
                'permissions' => 'file_manager',
                'action' => '',
                'icon' => '<i class="icon-file-image-o icon"></i>',
                'children' => [
                    // region: Media Browser
                    [
                        'text' => 'file_browser',
                        'description' => 'file_browser_desc',
                        'permissions' => 'file_manager',
                        'action' => 'media/browser',
                    ],
                    // endregion
                    // region: Media Sources
                    [
                        'text' => 'sources',
                        'description' => 'sources_desc',
                        'permissions' => 'sources',
                        'action' => 'source',
                    ],
                    // endregion
                ],
            ],
            // endregion
            // region: Components
            [
                'text' => 'components',
                'description' => '',
                'permissions' => 'components',
                'action' => '',
                'icon' => '<i class="icon-cube icon"></i>',
                'children' => [
                    // region: Installer
                    [
                        'text' => 'installer',
                        'description' => 'installer_desc',
                        'permissions' => 'packages',
                        'action' => 'workspaces',
                    ],
                    // endregion
                ],
            ],
            // endregion
        ],
    ],
    // endregion
    // region: User Navigation
    [
        'text' => 'usernav',
        'description' => 'usernav_desc',
        'permissions' => '',
        'action' => '',
        'children' => [
            // region: User
            [
                'text' => 'user',
                'description' => '',
                'permissions' => 'menu_user',
                'action' => '',
                'icon' => '<span id="user-avatar" title="{$username}">{$userImage}</span> <span id="user-username">{$username}</span>',
                'children' => [
                    // region: Profile
                    [
                        'text' => '{$username}',
                        'description' => 'profile_desc',
                        'permissions' => 'change_profile',
                        'action' => 'security/profile',
                    ],
                    // endregion
                    // region: Messages
                    [
                        'text' => 'messages',
                        'description' => 'messages_desc',
                        'permissions' => 'messages',
                        'action' => 'security/message',
                    ],
                    // endregion
                    // region: Logout
                    [
                        'text' => 'logout',
                        'description' => 'logout_desc',
                        'permissions' => 'logout',
                        'action' => 'security/logout',
                        'handler' => 'MODx.logout(); return false;',
                    ],
                    // endregion
                ],
            ],
            // endregion
            // region: Access
            [
                'text' => 'access',
                'description' => '',
                'permissions' => 'access_permissions',
                'action' => '',
                'icon' => '<i class="icon-user-lock icon"></i>',
                'children' => [
                    // region: Manage Users
                    [
                        'text' => 'users',
                        'description' => 'user_management_desc',
                        'permissions' => 'view_user',
                        'action' => 'security/user',
                    ],
                    // endregion
                    // region: Manage Resource Groups
                    [
                        'text' => 'resource_groups',
                        'description' => 'resource_groups_desc',
                        'permissions' => 'access_permissions',
                        'action' => 'security/resourcegroup',
                    ],
                    // endregion
                    // region: ACLs
                    [
                        'text' => 'acls',
                        'description' => 'acls_desc',
                        'permissions' => 'access_permissions',
                        'action' => 'security/permission',
                    ],
                    // endregion
                    // region: Flush Permissions
                    [
                        'text' => 'flush_access',
                        'description' => 'flush_access_desc',
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
                    ],
                    // endregion
                    // region: Flush Sessions
                    [
                        'text' => 'flush_sessions',
                        'description' => 'flush_sessions_desc',
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
                    ],
                    // endregion
                ],
            ],
            // endregion
            // region: Settings
            [
                'text' => 'admin',
                'description' => '',
                'permissions' => 'settings',
                'action' => '',
                'icon' => '<i class="icon-gear icon"></i>',
                'children' => [
                    // region: System Settings
                    [
                        'text' => 'system_settings',
                        'description' => 'system_settings_desc',
                        'permissions' => 'settings',
                        'action' => 'system/settings',
                    ],
                    // endregion
                    // region: Customize Manager
                    [
                        'text' => 'form_customization',
                        'description' => 'form_customization_desc',
                        'permissions' => 'customize_forms',
                        'action' => 'security/forms',
                    ],
                    // endregion
                    // region: Property Sets
                    [
                        'text' => 'propertysets',
                        'description' => 'propertysets_desc',
                        'permissions' => 'property_sets',
                        'action' => 'element/propertyset',
                    ],
                    // endregion
                    // region: Menus
                    [
                        'text' => 'edit_menu',
                        'description' => 'edit_menu_desc',
                        'permissions' => 'actions',
                        'action' => 'system/action',
                    ],
                    // endregion
                    // region: Contexts
                    [
                        'text' => 'contexts',
                        'description' => 'contexts_desc',
                        'permissions' => 'view_context',
                        'action' => 'context',
                    ],
                    // endregion
                    // region: Dashboards
                    [
                        'text' => 'dashboards',
                        'description' => 'dashboards_desc',
                        'permissions' => 'dashboards',
                        'action' => 'system/dashboards',
                    ],
                    // endregion
                    // region: Namespaces
                    [
                        'text' => 'namespaces',
                        'description' => 'namespaces_desc',
                        'permissions' => 'namespaces',
                        'action' => 'workspaces/namespace',
                    ],
                    // endregion
                    // region: Lexicons
                    [
                        'text' => 'lexicon_management',
                        'description' => 'lexicon_management_desc',
                        'permissions' => 'lexicons',
                        'action' => 'workspaces/lexicon',
                    ],
                    // endregion
                    // region: Toggle Language
                    [
                        'text' => 'language',
                        'description' => 'language_desc',
                        'permissions' => 'language',
                        'action' => ''
                    ],
                    // endregion
                    // region: Reports
                    [
                        'text' => 'reports',
                        'description' => 'reports_desc',
                        'permissions' => 'menu_reports',
                        'action' => '',
                        'children' => [
                            // region: Manager Actions
                            [
                                'text' => 'view_logging',
                                'description' => 'view_logging_desc',
                                'permissions' => 'mgr_log_view',
                                'action' => 'system/logs',
                            ],
                            // endregion
                            // region: Error Log
                            [
                                'text' => 'eventlog_viewer',
                                'description' => 'eventlog_viewer_desc',
                                'permissions' => 'view_eventlog',
                                'action' => 'system/event',
                            ],
                            // endregion
                            // region: System Info
                            [
                                'text' => 'view_sysinfo',
                                'description' => 'view_sysinfo_desc',
                                'permissions' => 'view_sysinfo',
                                'action' => 'system/info',
                            ],
                            // endregion
                        ],
                    ],
                    // endregion
                ],
            ],
            // endregion
            // region: About
            [
                'text' => 'about',
                'description' => 'about_desc',
                'permissions' => 'help',
                'action' => 'help',
                'icon' => '<i class="icon-question-circle icon"></i>',
            ],
            // endregion
        ],
    ]
    // endregion
];

function buildMenus($xpdo, $config, $parent = '')
{
    $menus = [];

    foreach ($config as $index => $menuItem) {
        $menuObject = $xpdo->newObject(modMenu::class);

        $children = [];
        if (isset($menuItem['children'])) {
            $children = $menuItem['children'];
            unset($menuItem['children']);
        }

        $menuItem['menuindex'] = $index;
        $menuItem['parent'] = $parent;
        $menuObject->fromArray($menuItem, '', true, true);

        if (!empty($children)) {
            $childMenuObjects = buildMenus($xpdo, $children, $menuItem['text']);
            $menuObject->addMany($childMenuObjects, 'Children');
        }

        $menus[] = $menuObject;
    }

    return $menus;
}

return buildMenus($xpdo, $menusConfig);
