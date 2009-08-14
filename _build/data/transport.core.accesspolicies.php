<?php
/**
 * Default MODx Access Policies
 *
 * @package modx
 * @subpackage build
 */
function bld_policyFormatData($permissions) {
    $data = array();
    foreach ($permissions as $permission) {
        $data[$permission->get('name')] = true;
    }
    return $data;
}
$collection['1']= $xpdo->newObject('modAccessPolicy');
$collection['1']->fromArray(array (
  'id' => 1,
  'name' => 'Resource',
  'description' => 'MODx Resource policy with all attributes.',
  'parent' => 0,
  'class' => '',
), '', true, true);
$permissions = include dirname(__FILE__).'/permissions/transport.policy.resource.php';
$collection['1']->addMany($permissions);
$collection['1']->set('data',bld_policyFormatData($permissions));
unset($permissions);


$collection['2']= $xpdo->newObject('modAccessPolicy');
$collection['2']->fromArray(array (
  'id' => 2,
  'name' => 'Administrator',
  'description' => 'Context administration policy with all permissions.',
  'parent' => 0,
  'class' => '',
), '', true, true);
$permissions = include dirname(__FILE__).'/permissions/transport.policy.administrator.php';
$collection['2']->addMany($permissions,'Permissions');
$collection['2']->set('data',bld_policyFormatData($permissions));
unset($permissions);
