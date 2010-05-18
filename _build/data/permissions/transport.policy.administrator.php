<?php
/**
 * The default Permission scheme for the Administrator Policy.
 *
 * @package modx
 */
$permissions = array();
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'about',
    'description' => 'perm.about_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'access_permissions',
    'description' => 'perm.access_permissions_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'actions',
    'description' => 'perm.actions_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'change_password',
    'description' => 'perm.change_password_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'change_profile',
    'description' => 'perm.change_profile_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'charsets',
    'description' => 'perm.charsets_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'content_types',
    'description' => 'perm.content_types_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'countries',
    'description' => 'perm.countries_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'create',
    'description' => 'perm.create_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'credits',
    'description' => 'perm.credits_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'customize_forms',
    'description' => 'perm.customize_forms_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'database',
    'description' => 'perm.database_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'database_truncate',
    'description' => 'perm.database_truncate_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'delete_category',
    'description' => 'perm.delete_category_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'delete_chunk',
    'description' => 'perm.delete_chunk_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'delete_context',
    'description' => 'perm.delete_context_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'delete_document',
    'description' => 'perm.delete_document_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'delete_eventlog',
    'description' => 'perm.delete_eventlog_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'delete_plugin',
    'description' => 'perm.delete_plugin_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'delete_propertyset',
    'description' => 'perm.delete_propertyset_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'delete_snippet',
    'description' => 'perm.delete_snippet_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'delete_template',
    'description' => 'perm.delete_template_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'delete_tv',
    'description' => 'perm.delete_tv_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'delete_role',
    'description' => 'perm.delete_role_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'delete_user',
    'description' => 'perm.delete_user_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'directory_chmod',
    'description' => 'perm.directory_chmod_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'directory_create',
    'description' => 'perm.directory_create_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'directory_list',
    'description' => 'perm.directory_list_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'directory_remove',
    'description' => 'perm.directory_remove_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'directory_update',
    'description' => 'perm.directory_update_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'edit_category',
    'description' => 'perm.edit_category_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'edit_chunk',
    'description' => 'perm.edit_chunk_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'edit_context',
    'description' => 'perm.edit_context_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'edit_document',
    'description' => 'perm.edit_document_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'edit_locked',
    'description' => 'perm.edit_locked_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'edit_plugin',
    'description' => 'perm.edit_plugin_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'edit_propertyset',
    'description' => 'perm.edit_propertyset_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'edit_role',
    'description' => 'perm.edit_role_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'edit_snippet',
    'description' => 'perm.edit_snippet_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'edit_template',
    'description' => 'perm.edit_template_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'edit_tv',
    'description' => 'perm.edit_tv_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'edit_user',
    'description' => 'perm.edit_user_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'element_tree',
    'description' => 'perm.element_tree_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'empty_cache',
    'description' => 'perm.empty_cache_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'error_log_erase',
    'description' => 'perm.error_log_erase_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'error_log_view',
    'description' => 'perm.error_log_view_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'export_static',
    'description' => 'perm.export_static_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'file_list',
    'description' => 'perm.file_list_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'file_manager',
    'description' => 'perm.file_manager_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'file_remove',
    'description' => 'perm.file_remove_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'file_tree',
    'description' => 'perm.file_tree_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'file_update',
    'description' => 'perm.file_update_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'file_upload',
    'description' => 'perm.file_upload_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'file_view',
    'description' => 'perm.file_view_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'flush_sessions',
    'description' => 'perm.flush_sessions_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'frames',
    'description' => 'perm.frames_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'help',
    'description' => 'perm.help_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'home',
    'description' => 'perm.home_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'import_static',
    'description' => 'perm.import_static_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'languages',
    'description' => 'perm.languages_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'lexicons',
    'description' => 'perm.lexicons_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'list',
    'description' => 'perm.list_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'load',
    'description' => 'perm.load_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'logout',
    'description' => 'perm.logout_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'logs',
    'description' => 'perm.logs_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'menus',
    'description' => 'perm.menus_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'messages',
    'description' => 'perm.messages_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'namespaces',
    'description' => 'perm.namespaces_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'new_category',
    'description' => 'perm.new_category_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'new_chunk',
    'description' => 'perm.new_chunk_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'new_context',
    'description' => 'perm.new_context_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'new_document',
    'description' => 'perm.new_document_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'new_document_in_root',
    'description' => 'perm.new_document_in_root_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'new_plugin',
    'description' => 'perm.new_plugin_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'new_propertyset',
    'description' => 'perm.new_propertyset_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'new_role',
    'description' => 'perm.new_role_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'new_snippet',
    'description' => 'perm.new_snippet_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'new_template',
    'description' => 'perm.new_template_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'new_tv',
    'description' => 'perm.new_tv_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'new_user',
    'description' => 'perm.new_user_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'packages',
    'description' => 'perm.packages_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'property_sets',
    'description' => 'perm.property_sets_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'providers',
    'description' => 'perm.providers_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'publish_document',
    'description' => 'perm.publish_document_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'purge_deleted',
    'description' => 'perm.purge_deleted_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'remove',
    'description' => 'perm.remove_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'remove_locks',
    'description' => 'perm.remove_locks_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'resource_tree',
    'description' => 'perm.resource_tree_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'save',
    'description' => 'perm.save_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'save_category',
    'description' => 'perm.save_category_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'save_chunk',
    'description' => 'perm.save_chunk_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'save_context',
    'description' => 'perm.save_context_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'save_document',
    'description' => 'perm.save_document_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'save_plugin',
    'description' => 'perm.save_plugin_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'save_propertyset',
    'description' => 'perm.save_propertyset_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'save_role',
    'description' => 'perm.save_role_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'save_snippet',
    'description' => 'perm.save_snippet_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'save_template',
    'description' => 'perm.save_template_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'save_tv',
    'description' => 'perm.save_tv_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'save_user',
    'description' => 'perm.save_user_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'search',
    'description' => 'perm.search_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'settings',
    'description' => 'perm.settings_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'steal_locks',
    'description' => 'perm.steal_locks_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'undelete_document',
    'description' => 'perm.undelete_document_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'unpublish_document',
    'description' => 'perm.unpublish_document_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'unlock_element_properties',
    'description' => 'perm.unlock_element_properties_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view',
    'description' => 'perm.view_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_category',
    'description' => 'perm.view_category_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_chunk',
    'description' => 'perm.view_chunk_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_context',
    'description' => 'perm.view_context_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_document',
    'description' => 'perm.view_document_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_element',
    'description' => 'perm.view_element_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_eventlog',
    'description' => 'perm.view_eventlog_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_offline',
    'description' => 'perm.view_offline_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_plugin',
    'description' => 'perm.view_plugin_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_propertyset',
    'description' => 'perm.view_propertyset_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_role',
    'description' => 'perm.view_role_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_snippet',
    'description' => 'perm.view_snippet_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_sysinfo',
    'description' => 'perm.view_sysinfo_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_template',
    'description' => 'perm.view_template_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_tv',
    'description' => 'perm.view_tv_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_user',
    'description' => 'perm.view_user_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'view_unpublished',
    'description' => 'perm.view_unpublished_desc',
    'value' => true,
));
$permissions[] = $xpdo->newObject('modAccessPermission',array(
    'name' => 'workspaces',
    'description' => 'perm.workspaces_desc',
    'value' => true,
));

return $permissions;