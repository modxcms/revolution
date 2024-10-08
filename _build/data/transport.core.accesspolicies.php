<?php
/**
 * Default MODX Access Policies
 *
 * @package modx
 * @subpackage build
 */
use MODX\Revolution\modAccessPolicy;

$policies = [];

function jsonifyPermissions(array $permissions = []) {
    $data = [];
    foreach ($permissions as $key => $permission) {
        if (is_string($key) && is_bool($permission)) {
            $data[$key] = $permission;
        } else {
            $data[$permission] = true;
        }
    }
    return json_encode($data);
}

$corePermissions = [
    modAccessPolicy::POLICY_RESOURCE => ['add_children', 'create', 'copy', 'delete', 'list', 'load', 'move', 'publish', 'remove', 'save', 'steal_lock', 'undelete', 'unpublish', 'view'],
    modAccessPolicy::POLICY_ADMINISTRATOR => ['access_permissions', 'actions', 'change_password', 'change_profile', 'charsets', 'class_map', 'content_types', 'countries', 'create', 'customize_forms', 'dashboards', 'database', 'database_truncate', 'delete_category', 'delete_chunk', 'delete_context', 'delete_document', 'delete_eventlog', 'delete_plugin', 'delete_propertyset', 'delete_role', 'delete_snippet', 'delete_static_resource', 'delete_symlink', 'delete_template', 'delete_tv', 'delete_user', 'delete_weblink', 'directory_chmod', 'directory_create', 'directory_list', 'directory_remove', 'directory_update', 'edit_category', 'edit_chunk', 'edit_context', 'edit_document', 'edit_locked', 'edit_plugin', 'edit_propertyset', 'edit_role', 'edit_snippet', 'edit_static_resource', 'edit_symlink', 'edit_template', 'edit_tv', 'edit_user', 'edit_weblink', 'element_tree', 'empty_cache', 'error_log_erase', 'error_log_view', 'events', 'file_create', 'file_list', 'file_manager', 'file_remove', 'file_tree', 'file_unpack', 'file_update', 'file_upload', 'file_view', 'flush_sessions', 'frames', 'help', 'home', 'language', 'languages', 'lexicons', 'list', 'load', 'logout', 'mgr_log_view', 'mgr_log_erase', 'menu_access', 'menu_content', 'menu_media', 'menu_packages', 'menu_reports', 'menu_system', 'menu_user', 'menus', 'messages', 'namespaces', 'new_category', 'new_chunk', 'new_context', 'new_document', 'new_document_in_root', 'new_plugin', 'new_propertyset', 'new_role', 'new_snippet', 'new_static_resource', 'new_symlink', 'new_template', 'new_tv', 'new_user', 'new_weblink', 'packages', 'policy_delete', 'policy_edit', 'policy_new', 'policy_save', 'policy_template_delete', 'policy_template_edit', 'policy_template_new', 'policy_template_save', 'policy_template_view', 'policy_view', 'property_sets', 'providers', 'publish_document', 'purge_deleted', 'remove', 'remove_locks', 'resource_duplicate', 'resource_quick_create', 'resource_quick_update', 'resource_tree', 'resourcegroup_delete', 'resourcegroup_edit', 'resourcegroup_new', 'resourcegroup_resource_edit', 'resourcegroup_resource_list', 'resourcegroup_save', 'resourcegroup_view', 'save', 'save_category', 'save_chunk', 'save_context', 'save_document', 'save_plugin', 'save_propertyset', 'save_role', 'save_snippet', 'save_template', 'save_tv', 'save_user', 'search', 'set_sudo', 'settings', 'source_delete', 'source_edit', 'source_save', 'source_view', 'sources', 'steal_locks', 'trash_view', 'tree_show_element_ids', 'tree_show_resource_ids', 'undelete_document', 'unlock_element_properties', 'unpublish_document', 'usergroup_delete', 'usergroup_edit', 'usergroup_new', 'usergroup_save', 'usergroup_user_edit', 'usergroup_user_list', 'usergroup_view', 'view', 'view_category', 'view_chunk', 'view_context', 'view_document', 'view_element', 'view_eventlog', 'view_offline', 'view_plugin', 'view_propertyset', 'view_role', 'view_snippet', 'view_sysinfo', 'view_template', 'view_tv', 'view_unpublished', 'view_user', 'workspaces'],
    modAccessPolicy::POLICY_LOAD_ONLY => ['load'],
    modAccessPolicy::POLICY_LOAD_LIST_VIEW => ['load', 'list', 'view'],
    modAccessPolicy::POLICY_OBJECT => ['load', 'list', 'view', 'save', 'remove'],
    modAccessPolicy::POLICY_ELEMENT => ['add_children', 'create', 'delete', 'list', 'load', 'remove', 'save', 'view', 'copy'],
    modAccessPolicy::POLICY_CONTENT_EDITOR => ['change_profile', 'class_map', 'countries', 'delete_document', 'delete_static_resource', 'delete_symlink', 'delete_weblink', 'edit_document', 'edit_static_resource', 'edit_symlink', 'edit_weblink', 'frames', 'help', 'home', 'language', 'list', 'load', 'logout', 'menu_content', 'menu_reports', 'menu_user', 'new_document', 'new_static_resource', 'new_symlink', 'new_weblink', 'resource_duplicate', 'resource_tree', 'save_document', 'source_view', 'tree_show_resource_ids', 'view', 'view_document', 'view_template'],
    modAccessPolicy::POLICY_MEDIA_SOURCE_ADMIN => ['create', 'copy', 'load', 'list', 'save', 'remove', 'view'],
    modAccessPolicy::POLICY_MEDIA_SOURCE_USER => ['load', 'list', 'view'],
    modAccessPolicy::POLICY_DEVELOPER => ['change_password', 'change_profile', 'charsets', 'class_map', 'content_types', 'countries', 'create', 'customize_forms', 'dashboards', 'database', 'delete_category', 'delete_chunk', 'delete_context', 'delete_document', 'delete_eventlog', 'delete_plugin', 'delete_propertyset', 'delete_role', 'delete_snippet', 'delete_template', 'delete_tv', 'delete_user', 'directory_chmod', 'directory_create', 'directory_list', 'directory_remove', 'directory_update', 'edit_category', 'edit_chunk', 'edit_context', 'edit_document', 'edit_locked', 'edit_plugin', 'edit_propertyset', 'edit_role', 'edit_snippet', 'edit_static_resource', 'edit_symlink', 'edit_template', 'edit_tv', 'edit_user', 'edit_weblink', 'element_tree', 'empty_cache', 'error_log_erase', 'error_log_view', 'file_create', 'file_list', 'file_manager', 'file_remove', 'file_tree', 'file_unpack', 'file_update', 'file_upload', 'file_view', 'frames', 'help', 'home', 'language', 'languages', 'lexicons', 'list', 'load', 'logout', 'mgr_log_view', 'mgr_log_erase', 'menu_access', 'menu_content', 'menu_media', 'menu_packages', 'menu_reports', 'menu_system', 'menu_user', 'menus', 'messages', 'namespaces', 'new_category', 'new_chunk', 'new_context', 'new_document', 'new_document_in_root', 'new_plugin', 'new_propertyset', 'new_role', 'new_snippet', 'new_static_resource', 'new_symlink', 'new_template', 'new_tv', 'new_user', 'new_weblink', 'packages', 'property_sets', 'providers', 'publish_document', 'purge_deleted', 'remove', 'resource_duplicate', 'resource_quick_create', 'resource_quick_update', 'resource_tree', 'save', 'save_category', 'save_chunk', 'save_context', 'save_document', 'save_plugin', 'save_propertyset', 'save_snippet', 'save_template', 'save_tv', 'save_user', 'search', 'settings', 'source_delete', 'source_edit', 'source_save', 'source_view', 'sources', 'tree_show_element_ids', 'tree_show_resource_ids', 'undelete_document', 'unlock_element_properties', 'unpublish_document', 'view', 'view_category', 'view_chunk', 'view_context', 'view_document', 'view_element', 'view_eventlog', 'view_offline', 'view_plugin', 'view_propertyset', 'view_role', 'view_snippet', 'view_sysinfo', 'view_template', 'view_tv', 'view_unpublished', 'view_user', 'workspaces'],
    modAccessPolicy::POLICY_CONTEXT => ['load', 'list', 'view', 'save', 'remove', 'copy', 'view_unpublished'],
    modAccessPolicy::POLICY_HIDDEN_NAMESPACE => ['load' => false, 'list' => false, 'view' => true],
];

foreach (modAccessPolicy::getCorePolicies() as $index => $policyName) {

    $policyNameLowered = str_replace([',', ' '], ['', '_'], strtolower($policyName));

    $policy = $xpdo->newObject(modAccessPolicy::class);
    $policy->fromArray(
        [
            'id' => $index + 1,
            'name' => $policyName,
            'description' => sprintf('policy_%s_desc', $policyNameLowered),
            'parent' => 0,
            'class' => '',
            'data' => jsonifyPermissions($corePermissions[$policyName]),
            'lexicon' => 'permissions',
        ],
        '',
        true,
        true
    );

    $policies[] = $policy;
}

return $policies;
