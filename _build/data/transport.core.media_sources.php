<?php
/**
 * Default Media Sources
 * @var xPDO $xpdo
 */

use MODX\Revolution\Sources\modMediaSource;

$collection[1]= $xpdo->newObject(modMediaSource::class);
$collection[1]->fromArray(array (
  'id' => 1,
  'name' => 'Filesystem',
  'description' => '',
  'class_key' => 'MODX\Revolution\Sources\modFileMediaSource',
  'properties' => array(),
), '', true, true);
