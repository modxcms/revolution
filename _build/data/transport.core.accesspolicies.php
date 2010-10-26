<?php
/**
 * Default MODx Access Policies
 *
 * @package modx
 * @subpackage build
 */
/*
function bld_policyFormatData($permissions) {
    $data = array();
    foreach ($permissions as $permission) {
        $data[$permission->get('name')] = true;
    }
    return $data;
}
 */

/* administrator group templates */
$templateGroups['1']= $xpdo->newObject('modAccessPolicyTemplateGroup');
$templateGroups['1']->fromArray(array(
    'id' => 1,
    'name' => 'Administrator',
    'description' => 'All administrator policy templates.',
));
$templates = array();

/* administrator template/policy */
$templates['1']= $xpdo->newObject('modAccessPolicyTemplate');
$templates['1']->fromArray(array(
    'id' => 1,
    'name' => 'Administrator',
    'description' => 'Context administration policy template with all permissions.',
    'lexicon' => 'permissions',
));
$permissions = include dirname(__FILE__).'/permissions/transport.policy.tpl.administrator.php';
if (is_array($permissions)) {
    $templates['1']->addMany($permissions);
} else { $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load Administrator Policy Template.'); }
unset($permissions);

$policies['2']= $xpdo->newObject('modAccessPolicy');
$policies['2']->fromArray(array (
  'id' => 2,
  'name' => 'Administrator',
  'description' => 'Context administration policy with all permissions.',
  'parent' => 0,
  'class' => '',
  'data' => '{"about":true,"access_permissions":true,"actions":true,"change_password":true,"change_profile":true,"charsets":true,"class_map":true,"content_types":true,"countries":true,"create":true,"credits":true,"customize_forms":true,"database":true,"database_truncate":true,"delete_category":true,"delete_chunk":true,"delete_context":true,"delete_document":true,"delete_eventlog":true,"delete_plugin":true,"delete_propertyset":true,"delete_snippet":true,"delete_template":true,"delete_tv":true,"delete_role":true,"delete_user":true,"directory_chmod":true,"directory_create":true,"directory_list":true,"directory_remove":true,"directory_update":true,"edit_category":true,"edit_chunk":true,"edit_context":true,"edit_document":true,"edit_locked":true,"edit_plugin":true,"edit_propertyset":true,"edit_role":true,"edit_snippet":true,"edit_template":true,"edit_tv":true,"edit_user":true,"element_tree":true,"empty_cache":true,"error_log_erase":true,"error_log_view":true,"export_static":true,"file_list":true,"file_manager":true,"file_remove":true,"file_tree":true,"file_update":true,"file_upload":true,"file_view":true,"flush_sessions":true,"frames":true,"help":true,"home":true,"import_static":true,"languages":true,"lexicons":true,"list":true,"load":true,"logout":true,"logs":true,"menus":true,"messages":true,"namespaces":true,"new_category":true,"new_chunk":true,"new_context":true,"new_document":true,"new_document_in_root":true,"new_plugin":true,"new_propertyset":true,"new_role":true,"new_snippet":true,"new_template":true,"new_tv":true,"new_user":true,"packages":true,"property_sets":true,"providers":true,"publish_document":true,"purge_deleted":true,"remove":true,"remove_locks":true,"resource_tree":true,"save":true,"save_category":true,"save_chunk":true,"save_context":true,"save_document":true,"save_plugin":true,"save_propertyset":true,"save_role":true,"save_snippet":true,"save_template":true,"save_tv":true,"save_user":true,"search":true,"settings":true,"steal_locks":true,"undelete_document":true,"unpublish_document":true,"unlock_element_properties":true,"view":true,"view_category":true,"view_chunk":true,"view_context":true,"view_document":true,"view_element":true,"view_eventlog":true,"view_offline":true,"view_plugin":true,"view_propertyset":true,"view_role":true,"view_snippet":true,"view_sysinfo":true,"view_template":true,"view_tv":true,"view_user":true,"view_unpublished":true,"workspaces":true}',
  'lexicon' => 'permissions',
), '', true, true);

$templates['1']->addMany($policies);
$templateGroups['1']->addMany($templates);
unset($policies,$templates);


/* Object group templates */
$templateGroups['2']= $xpdo->newObject('modAccessPolicyTemplateGroup');
$templateGroups['2']->fromArray(array(
    'id' => 2,
    'name' => 'Object',
    'description' => 'All Object-based policy templates.',
));
$templates = array();

/* resource template/policy */
$templates['2']= $xpdo->newObject('modAccessPolicyTemplate');
$templates['2']->fromArray(array(
    'id' => 1,
    'name' => 'Resource',
    'description' => 'Resource Policy Template with all attributes.',
    'lexicon' => 'permissions',
));
$permissions = include dirname(__FILE__).'/permissions/transport.policy.tpl.resource.php';
if (is_array($permissions)) {
    $templates['2']->addMany($permissions);
} else { $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load Resource Template Permissions.'); }
unset($permissions);

$policies = array();
$policies['1']= $xpdo->newObject('modAccessPolicy');
$policies['1']->fromArray(array (
  'id' => 1,
  'name' => 'Resource',
  'description' => 'MODx Resource Policy with all attributes.',
  'parent' => 0,
  'class' => '',
  'data' => '{"add_children":true,"create":true,"delete":true,"list":true,"load":true,"move":true,"publish":true,"remove":true,"save":true,"steal_lock":true,"undelete":true,"unpublish":true,"view":true}',
  'lexicon' => 'permissions',
), '', true, true);
$templates['2']->addMany($policies);
unset($policies);


/* object template and policies */
$templates['3']= $xpdo->newObject('modAccessPolicyTemplate');
$templates['3']->fromArray(array(
    'id' => 2,
    'name' => 'Object',
    'description' => 'Object Policy Template with all attributes.',
    'lexicon' => 'permissions',
));
$permissions = include dirname(__FILE__).'/permissions/transport.policy.tpl.object.php';
if (is_array($permissions)) {
    $templates['3']->addMany($permissions);
} else { $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load Object Template Permissions.'); }

$policies = array();
$policies['3']= $xpdo->newObject('modAccessPolicy');
$policies['3']->fromArray(array (
  'id' => 3,
  'name' => 'Load Only',
  'description' => 'A minimal policy with permission to load an object.',
  'parent' => 0,
  'class' => '',
  'data' => '{"load":true}',
  'lexicon' => 'permissions',
), '', true, true);

$policies['4']= $xpdo->newObject('modAccessPolicy');
$policies['4']->fromArray(array (
  'id' => 4,
  'name' => 'Load, List and View',
  'description' => 'Provides load, list and view permissions only.',
  'parent' => 0,
  'class' => '',
  'data' => '{"load":true,"list":true,"view":true}',
  'lexicon' => 'permissions',
), '', true, true);

$policies['5']= $xpdo->newObject('modAccessPolicy');
$policies['5']->fromArray(array (
  'id' => 5,
  'name' => 'Object',
  'description' => 'An Object policy with all permissions.',
  'parent' => 0,
  'class' => '',
  'data' => '{"load":true,"list":true,"view":true,"save":true,"remove":true}',
  'lexicon' => 'permissions',
), '', true, true);

$templates['3']->addMany($policies);
unset($policies);

/* element template/policy */
$templates['4']= $xpdo->newObject('modAccessPolicyTemplate');
$templates['4']->fromArray(array(
    'id' => 3,
    'name' => 'Element',
    'description' => 'Element Policy Template with all attributes.',
    'lexicon' => 'permissions',
));
$permissions = include dirname(__FILE__).'/permissions/transport.policy.tpl.element.php';
if (is_array($permissions)) {
    $templates['4']->addMany($permissions);
} else { $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load Element Template Permissions.'); }

$policies = array();
$policies['6']= $xpdo->newObject('modAccessPolicy');
$policies['6']->fromArray(array (
  'id' => 6,
  'name' => 'Element',
  'description' => 'MODx Element policy with all attributes.',
  'parent' => 0,
  'class' => '',
  'data' => '{"create":true,"delete":true,"list":true,"load":true,"remove":true,"save":true,"view":true}',
  'lexicon' => 'permissions',
), '', true, true);

$templates['4']->addMany($policies);
unset($policies);
$templateGroups['2']->addMany($templates);
unset($templates);

return $templateGroups;
