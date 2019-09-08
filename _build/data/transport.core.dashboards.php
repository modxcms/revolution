<?php
/**
 * Default Dashboards
 */
use MODX\Revolution\modDashboard;

$collection[1]= $xpdo->newObject(modDashboard::class);
$collection[1]->fromArray(array (
  'id' => 1,
  'name' => 'Default',
  'description' => '',
), '', true, true);
