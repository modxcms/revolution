<?php
use MODX\Revolution\modUserGroupRole;

$collection['1']= $xpdo->newObject(modUserGroupRole::class);
$collection['1']->fromArray(array (
  'id' => 1,
  'name' => 'Member',
  'description' => 'NULL',
  'authority' => 9999,
), '', true, true);
$collection['2']= $xpdo->newObject(modUserGroupRole::class);
$collection['2']->fromArray(array (
  'id' => 2,
  'name' => 'Super User',
  'description' => 'NULL',
  'authority' => 0,
), '', true, true);
