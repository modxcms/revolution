<?php
use MODX\Revolution\modContextSetting;

$collection['0']= $xpdo->newObject(modContextSetting::class);
$collection['0']->fromArray([
  'context_key' => 'mgr',
  'key' => 'allow_tags_in_post',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => NULL,
], '', true, true);
$collection['1']= $xpdo->newObject(modContextSetting::class);
$collection['1']->fromArray([
  'context_key' => 'mgr',
  'key' => 'modRequest.class',
  'value' => 'MODX\Revolution\modManagerRequest',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => NULL,
], '', true, true);
