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
  'lexicon' => 'permissions',
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
  'lexicon' => 'permissions',
), '', true, true);
$permissions = include dirname(__FILE__).'/permissions/transport.policy.administrator.php';
$collection['2']->addMany($permissions);
$collection['2']->set('data',bld_policyFormatData($permissions));
unset($permissions);


$collection['3']= $xpdo->newObject('modAccessPolicy');
$collection['3']->fromArray(array (
  'id' => 3,
  'name' => 'Load Only',
  'description' => 'A minimal policy with permission to load an object.',
  'parent' => 0,
  'class' => '',
  'lexicon' => 'permissions',
), '', true, true);
$permissions = include dirname(__FILE__).'/permissions/transport.policy.loadonly.php';
$collection['3']->addMany($permissions);
$collection['3']->set('data',bld_policyFormatData($permissions));
unset($permissions);

$collection['4']= $xpdo->newObject('modAccessPolicy');
$collection['4']->fromArray(array (
  'id' => 4,
  'name' => 'Load, List and View',
  'description' => 'Provides load, list and view permissions only.',
  'parent' => 0,
  'class' => '',
  'lexicon' => 'permissions',
), '', true, true);
$permissions = include dirname(__FILE__).'/permissions/transport.policy.loadlistview.php';
$collection['4']->addMany($permissions);
$collection['4']->set('data',bld_policyFormatData($permissions));
unset($permissions);

$collection['5']= $xpdo->newObject('modAccessPolicy');
$collection['5']->fromArray(array (
  'id' => 5,
  'name' => 'Element',
  'description' => 'MODx Element policy with all attributes.',
  'parent' => 0,
  'class' => '',
  'lexicon' => 'permissions',
), '', true, true);
$permissions = include dirname(__FILE__).'/permissions/transport.policy.element.php';
$collection['5']->addMany($permissions);
$collection['5']->set('data',bld_policyFormatData($permissions));
unset($permissions);
