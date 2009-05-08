<?php
$collection['1']= $xpdo->newObject('modAccessPolicy');
$collection['1']->fromArray(array (
  'id' => 1,
  'name' => 'Resource',
  'description' => 'MODx Resource policy with all attributes.',
  'parent' => 0,
  'class' => '',
  'data' => '{"create":true,"remove":true,"save":true,"load":true,"list":true,"view":true,"move":true,"publish":true,"unpublish":true,"delete":true,"undelete":true,"add_children":true}',
), '', true, true);
$collection['2']= $xpdo->newObject('modAccessPolicy');
$collection['2']->fromArray(array (
  'id' => 2,
  'name' => 'Context',
  'description' => 'Context administration policy with all permissions.',
  'parent' => 0,
  'class' => '',
  'data' => '{"create":true,"remove":true,"save":true,"load":true,"frames":true,"home":true,"view_document":true,"new_document":true,"save_document":true,"publish_document":true,"delete_document":true,"action_ok":true,"logout":true,"help":true,"messages":true,"new_user":true,"edit_user":true,"logs":true,"edit_parser":true,"save_parser":true,"edit_template":true,"settings":true,"credits":true,"new_template":true,"save_template":true,"delete_template":true,"edit_snippet":true,"new_snippet":true,"save_snippet":true,"delete_snippet":true,"edit_chunk":true,"new_chunk":true,"save_chunk":true,"delete_chunk":true,"empty_cache":true,"edit_document":true,"change_password":true,"error_dialog":true,"about":true,"file_manager":true,"save_user":true,"delete_user":true,"save_password":true,"edit_role":true,"save_role":true,"delete_role":true,"new_role":true,"access_permissions":true,"new_plugin":true,"edit_plugin":true,"save_plugin":true,"delete_plugin":true,"view_eventlog":true,"delete_eventlog":true,"manage_metatags":true,"edit_doc_metatags":true,"view_unpublished":true,"import_static":true,"export_static":true,"edit_locked":true,"flush_sessions":true,"list":true,"view":true,"purge_deleted":true,"change_profile":true,"remove_locks":true,"database":true,"database_truncate":true,"content_types":true,"actions":true,"menus":true,"languages":true,"package_builder":true,"packages":true,"providers":true,"lexicons":true,"namespaces":true,"workspaces":true,"search":true,"view_context":true,"delete_context":true,"new_context":true,"edit_context":true,"view_offline":true,"unlock_element_properties":true,"steal_locks":true}',
), '', true, true);
