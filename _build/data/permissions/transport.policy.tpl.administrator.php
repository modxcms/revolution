<?php
/**
 * The default Permission scheme for the Administrator Policy Template.
 *
 * @package modx
 */
use MODX\Revolution\modAccessPermission;

$permissions = [];
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'about',
    'description' => 'perm.about_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'access_permissions',
    'description' => 'perm.access_permissions_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'actions',
    'description' => 'perm.actions_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'change_password',
    'description' => 'perm.change_password_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'change_profile',
    'description' => 'perm.change_profile_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'charsets',
    'description' => 'perm.charsets_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'class_map',
    'description' => 'perm.class_map_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'components',
    'description' => 'perm.components_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'content_types',
    'description' => 'perm.content_types_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'countries',
    'description' => 'perm.countries_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'create',
    'description' => 'perm.create_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'credits',
    'description' => 'perm.credits_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'customize_forms',
    'description' => 'perm.customize_forms_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'dashboards',
    'description' => 'perm.dashboards_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'database',
    'description' => 'perm.database_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'database_truncate',
    'description' => 'perm.database_truncate_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'delete_category',
    'description' => 'perm.delete_category_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'delete_chunk',
    'description' => 'perm.delete_chunk_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'delete_context',
    'description' => 'perm.delete_context_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'delete_document',
    'description' => 'perm.delete_document_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'delete_eventlog',
    'description' => 'perm.delete_eventlog_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'delete_plugin',
    'description' => 'perm.delete_plugin_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'delete_propertyset',
    'description' => 'perm.delete_propertyset_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'delete_snippet',
    'description' => 'perm.delete_snippet_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'delete_template',
    'description' => 'perm.delete_template_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'delete_tv',
    'description' => 'perm.delete_tv_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'delete_role',
    'description' => 'perm.delete_role_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'delete_user',
    'description' => 'perm.delete_user_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'directory_chmod',
    'description' => 'perm.directory_chmod_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'directory_create',
    'description' => 'perm.directory_create_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'directory_list',
    'description' => 'perm.directory_list_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'directory_remove',
    'description' => 'perm.directory_remove_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'directory_update',
    'description' => 'perm.directory_update_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'edit_category',
    'description' => 'perm.edit_category_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'edit_chunk',
    'description' => 'perm.edit_chunk_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'edit_context',
    'description' => 'perm.edit_context_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'edit_document',
    'description' => 'perm.edit_document_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'edit_locked',
    'description' => 'perm.edit_locked_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'edit_plugin',
    'description' => 'perm.edit_plugin_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'edit_propertyset',
    'description' => 'perm.edit_propertyset_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'edit_role',
    'description' => 'perm.edit_role_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'edit_snippet',
    'description' => 'perm.edit_snippet_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'edit_template',
    'description' => 'perm.edit_template_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'edit_tv',
    'description' => 'perm.edit_tv_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'edit_user',
    'description' => 'perm.edit_user_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'element_tree',
    'description' => 'perm.element_tree_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'empty_cache',
    'description' => 'perm.empty_cache_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'error_log_erase',
    'description' => 'perm.error_log_erase_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'error_log_view',
    'description' => 'perm.error_log_view_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'export_static',
    'description' => 'perm.export_static_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'file_create',
    'description' => 'perm.file_create_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'file_list',
    'description' => 'perm.file_list_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'file_manager',
    'description' => 'perm.file_manager_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'file_remove',
    'description' => 'perm.file_remove_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'file_tree',
    'description' => 'perm.file_tree_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'file_update',
    'description' => 'perm.file_update_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'file_upload',
    'description' => 'perm.file_upload_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'file_unpack',
    'description' => 'perm.file_unpack_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'file_view',
    'description' => 'perm.file_view_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'flush_sessions',
    'description' => 'perm.flush_sessions_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'frames',
    'description' => 'perm.frames_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'help',
    'description' => 'perm.help_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'home',
    'description' => 'perm.home_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'import_static',
    'description' => 'perm.import_static_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'language',
    'description' => 'perm.language_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'languages',
    'description' => 'perm.languages_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'lexicons',
    'description' => 'perm.lexicons_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'list',
    'description' => 'perm.list_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'load',
    'description' => 'perm.load_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'logout',
    'description' => 'perm.logout_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'logs',
    'description' => 'perm.logs_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'menu_reports',
    'description' => 'perm.menu_reports_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'menu_security',
    'description' => 'perm.menu_security_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'menu_site',
    'description' => 'perm.menu_site_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'menu_support',
    'description' => 'perm.menu_support_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'menu_system',
    'description' => 'perm.menu_system_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'menu_tools',
    'description' => 'perm.menu_tools_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'menu_trash',
    'description' => 'perm.menu_trash_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'menu_user',
    'description' => 'perm.menu_user_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'menus',
    'description' => 'perm.menus_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'messages',
    'description' => 'perm.messages_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'namespaces',
    'description' => 'perm.namespaces_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'new_category',
    'description' => 'perm.new_category_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'new_chunk',
    'description' => 'perm.new_chunk_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'new_context',
    'description' => 'perm.new_context_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'new_document',
    'description' => 'perm.new_document_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'new_static_resource',
    'description' => 'perm.new_static_resource_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'new_symlink',
    'description' => 'perm.new_symlink_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'new_weblink',
    'description' => 'perm.new_weblink_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'new_document_in_root',
    'description' => 'perm.new_document_in_root_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'new_plugin',
    'description' => 'perm.new_plugin_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'new_propertyset',
    'description' => 'perm.new_propertyset_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'new_role',
    'description' => 'perm.new_role_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'new_snippet',
    'description' => 'perm.new_snippet_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'new_template',
    'description' => 'perm.new_template_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'new_tv',
    'description' => 'perm.new_tv_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'new_user',
    'description' => 'perm.new_user_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'packages',
    'description' => 'perm.packages_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'policy_delete',
    'description' => 'perm.policy_delete_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'policy_edit',
    'description' => 'perm.policy_edit_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'policy_new',
    'description' => 'perm.policy_new_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'policy_save',
    'description' => 'perm.policy_save_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'policy_view',
    'description' => 'perm.policy_view_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'policy_template_delete',
    'description' => 'perm.policy_template_delete_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'policy_template_edit',
    'description' => 'perm.policy_template_edit_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'policy_template_new',
    'description' => 'perm.policy_template_new_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'policy_template_save',
    'description' => 'perm.policy_template_save_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'policy_template_view',
    'description' => 'perm.policy_template_view_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'property_sets',
    'description' => 'perm.property_sets_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'providers',
    'description' => 'perm.providers_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'publish_document',
    'description' => 'perm.publish_document_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'purge_deleted',
    'description' => 'perm.purge_deleted_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'remove',
    'description' => 'perm.remove_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'remove_locks',
    'description' => 'perm.remove_locks_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'resource_duplicate',
    'description' => 'perm.resource_duplicate_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'resourcegroup_delete',
    'description' => 'perm.resourcegroup_delete_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'resourcegroup_edit',
    'description' => 'perm.resourcegroup_edit_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'resourcegroup_new',
    'description' => 'perm.resourcegroup_new_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'resourcegroup_resource_edit',
    'description' => 'perm.resourcegroup_resource_edit_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'resourcegroup_resource_list',
    'description' => 'perm.resourcegroup_resource_list_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'resourcegroup_save',
    'description' => 'perm.resourcegroup_save_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'resourcegroup_view',
    'description' => 'perm.resourcegroup_view_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'resource_quick_create',
    'description' => 'perm.resource_quick_create_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'resource_quick_update',
    'description' => 'perm.resource_quick_update_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'resource_tree',
    'description' => 'perm.resource_tree_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'save',
    'description' => 'perm.save_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'save_category',
    'description' => 'perm.save_category_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'save_chunk',
    'description' => 'perm.save_chunk_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'save_context',
    'description' => 'perm.save_context_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'save_document',
    'description' => 'perm.save_document_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'save_plugin',
    'description' => 'perm.save_plugin_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'save_propertyset',
    'description' => 'perm.save_propertyset_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'save_role',
    'description' => 'perm.save_role_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'save_snippet',
    'description' => 'perm.save_snippet_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'save_template',
    'description' => 'perm.save_template_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'save_tv',
    'description' => 'perm.save_tv_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'save_user',
    'description' => 'perm.save_user_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'search',
    'description' => 'perm.search_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'set_sudo',
    'description' => 'perm.set_sudo_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'settings',
    'description' => 'perm.settings_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'events',
    'description' => 'perm.events_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'source_save',
    'description' => 'perm.source_save_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'source_delete',
    'description' => 'perm.source_delete_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'source_edit',
    'description' => 'perm.source_edit_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'source_view',
    'description' => 'perm.source_view_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'sources',
    'description' => 'perm.sources_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'steal_locks',
    'description' => 'perm.steal_locks_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'tree_show_element_ids',
    'description' => 'perm.tree_show_element_ids_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'tree_show_resource_ids',
    'description' => 'perm.tree_show_resource_ids_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'undelete_document',
    'description' => 'perm.undelete_document_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'unpublish_document',
    'description' => 'perm.unpublish_document_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'unlock_element_properties',
    'description' => 'perm.unlock_element_properties_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'usergroup_delete',
    'description' => 'perm.usergroup_delete_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'usergroup_edit',
    'description' => 'perm.usergroup_edit_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'usergroup_new',
    'description' => 'perm.usergroup_new_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'usergroup_save',
    'description' => 'perm.usergroup_save_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'usergroup_user_edit',
    'description' => 'perm.usergroup_user_edit_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'usergroup_user_list',
    'description' => 'perm.usergroup_user_list_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'usergroup_view',
    'description' => 'perm.usergroup_view_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view',
    'description' => 'perm.view_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_category',
    'description' => 'perm.view_category_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_chunk',
    'description' => 'perm.view_chunk_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_context',
    'description' => 'perm.view_context_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_document',
    'description' => 'perm.view_document_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_element',
    'description' => 'perm.view_element_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_eventlog',
    'description' => 'perm.view_eventlog_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_offline',
    'description' => 'perm.view_offline_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_plugin',
    'description' => 'perm.view_plugin_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_propertyset',
    'description' => 'perm.view_propertyset_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_role',
    'description' => 'perm.view_role_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_snippet',
    'description' => 'perm.view_snippet_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_sysinfo',
    'description' => 'perm.view_sysinfo_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_template',
    'description' => 'perm.view_template_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_tv',
    'description' => 'perm.view_tv_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_user',
    'description' => 'perm.view_user_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'view_unpublished',
    'description' => 'perm.view_unpublished_desc',
    'value' => true,
]);
$permissions[] = $xpdo->newObject(modAccessPermission::class, [
    'name' => 'workspaces',
    'description' => 'perm.workspaces_desc',
    'value' => true,
]);

return $permissions;
