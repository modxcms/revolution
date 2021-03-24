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
    modAccessPolicy::POLICY_ADMINISTRATOR => ['about', 'access_permissions', 'actions', 'change_password', 'change_profile', 'charsets', 'class_map', 'components', 'content_types', 'countries', 'create', 'credits', 'customize_forms', 'dashboards', 'database', 'database_truncate', 'delete_category', 'delete_chunk', 'delete_context', 'delete_document', 'delete_eventlog', 'delete_plugin', 'delete_propertyset', 'delete_role', 'delete_snippet', 'delete_template', 'delete_tv', 'delete_user', 'directory_chmod', 'directory_create', 'directory_list', 'directory_remove', 'directory_update', 'edit_category', 'edit_chunk', 'edit_context', 'edit_document', 'edit_locked', 'edit_plugin', 'edit_propertyset', 'edit_role', 'edit_snippet', 'edit_template', 'edit_tv', 'edit_user', 'element_tree', 'empty_cache', 'error_log_erase', 'error_log_view', 'events', 'export_static', 'file_create', 'file_list', 'file_manager', 'file_remove', 'file_tree', 'file_update', 'file_upload', 'file_unpack', 'file_view', 'flush_sessions', 'frames', 'help', 'home', 'import_static', 'languages', 'lexicons', 'list', 'load', 'logout', 'logs', 'menus', 'menu_reports', 'menu_security', 'menu_site', 'menu_support', 'menu_system', 'menu_tools', 'menu_user', 'messages', 'namespaces', 'new_category', 'new_chunk', 'new_context', 'new_document', 'new_document_in_root', 'new_plugin', 'new_propertyset', 'new_role', 'new_snippet', 'new_static_resource', 'new_symlink', 'new_template', 'new_tv', 'new_user', 'new_weblink', 'packages', 'policy_delete', 'policy_edit', 'policy_new', 'policy_save', 'policy_template_delete', 'policy_template_edit', 'policy_template_new', 'policy_template_save', 'policy_template_view', 'policy_view', 'property_sets', 'providers', 'publish_document', 'purge_deleted', 'remove', 'remove_locks', 'resource_duplicate', 'resourcegroup_delete', 'resourcegroup_edit', 'resourcegroup_new', 'resourcegroup_resource_edit', 'resourcegroup_resource_list', 'resourcegroup_save', 'resourcegroup_view', 'resource_quick_create', 'resource_quick_update', 'resource_tree', 'save', 'save_category', 'save_chunk', 'save_context', 'save_document', 'save_plugin', 'save_propertyset', 'save_role', 'save_snippet', 'save_template', 'save_tv', 'save_user', 'search', 'set_sudo', 'settings', 'sources', 'source_delete', 'source_edit', 'source_save', 'source_view', 'steal_locks', 'tree_show_element_ids', 'tree_show_resource_ids', 'undelete_document', 'unlock_element_properties', 'unpublish_document', 'usergroup_delete', 'usergroup_edit', 'usergroup_new', 'usergroup_save', 'usergroup_user_edit', 'usergroup_user_list', 'usergroup_view', 'view', 'view_category', 'view_chunk', 'view_context', 'view_document', 'view_element', 'view_eventlog', 'view_offline', 'view_plugin', 'view_propertyset', 'view_role', 'view_snippet', 'view_sysinfo', 'view_template', 'view_tv', 'view_unpublished', 'view_user', 'workspaces'],
    modAccessPolicy::POLICY_LOAD_ONLY => ['load'],
    modAccessPolicy::POLICY_LOAD_LIST_VIEW => ['load', 'list', 'view'],
    modAccessPolicy::POLICY_OBJECT => ['load', 'list', 'view', 'save', 'remove'],
    modAccessPolicy::POLICY_ELEMENT => ['add_children', 'create', 'delete', 'list', 'load', 'remove', 'save', 'view', 'copy'],
    modAccessPolicy::POLICY_CONTENT_EDITOR => ['change_profile', 'class_map', 'countries', 'edit_document', 'frames', 'help', 'home', 'load', 'list', 'logout', 'menu_reports', 'menu_site', 'menu_support', 'menu_tools', 'menu_user', 'resource_duplicate', 'resource_tree', 'save_document', 'source_view', 'tree_show_resource_ids', 'view', 'view_document', 'view_template', 'new_document', 'delete_document'],
    modAccessPolicy::POLICY_MEDIA_SOURCE_ADMIN => ['create', 'copy', 'load', 'list', 'save', 'remove', 'view'],
    modAccessPolicy::POLICY_MEDIA_SOURCE_USER => ['load', 'list', 'view'],
    modAccessPolicy::POLICY_DEVELOPER => ['about', 'change_password', 'change_profile', 'charsets', 'class_map', 'components', 'content_types', 'countries', 'create', 'credits', 'customize_forms', 'dashboards', 'database', 'delete_category', 'delete_chunk', 'delete_context', 'delete_document', 'delete_eventlog', 'delete_plugin', 'delete_propertyset', 'delete_snippet', 'delete_template', 'delete_tv', 'delete_role', 'delete_user', 'directory_chmod', 'directory_create', 'directory_list', 'directory_remove', 'directory_update', 'edit_category', 'edit_chunk', 'edit_context', 'edit_document', 'edit_locked', 'edit_plugin', 'edit_propertyset', 'edit_role', 'edit_snippet', 'edit_template', 'edit_tv', 'edit_user', 'element_tree', 'empty_cache', 'error_log_erase', 'error_log_view', 'export_static', 'file_create', 'file_list', 'file_manager', 'file_remove', 'file_tree', 'file_update', 'file_upload', 'file_unpack', 'file_view', 'frames', 'help', 'home', 'import_static', 'languages', 'lexicons', 'list', 'load', 'logout', 'logs', 'menu_reports', 'menu_site', 'menu_support', 'menu_system', 'menu_tools', 'menu_user', 'menus', 'messages', 'namespaces', 'new_category', 'new_chunk', 'new_context', 'new_document', 'new_static_resource', 'new_symlink', 'new_weblink', 'new_document_in_root', 'new_plugin', 'new_propertyset', 'new_role', 'new_snippet', 'new_template', 'new_tv', 'new_user', 'packages', 'property_sets', 'providers', 'publish_document', 'purge_deleted', 'remove', 'resource_duplicate', 'resource_quick_create', 'resource_quick_update', 'resource_tree', 'save', 'save_category', 'save_chunk', 'save_context', 'save_document', 'save_plugin', 'save_propertyset', 'save_snippet', 'save_template', 'save_tv', 'save_user', 'search', 'settings', 'source_delete', 'source_edit', 'source_save', 'source_view', 'sources', 'tree_show_element_ids', 'tree_show_resource_ids', 'undelete_document', 'unpublish_document', 'unlock_element_properties', 'view', 'view_category', 'view_chunk', 'view_context', 'view_document', 'view_element', 'view_eventlog', 'view_offline', 'view_plugin', 'view_propertyset', 'view_role', 'view_snippet', 'view_sysinfo', 'view_template', 'view_tv', 'view_user', 'view_unpublished', 'workspaces'],
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
