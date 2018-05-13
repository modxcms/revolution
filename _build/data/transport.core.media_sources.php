<?php
/**
 * Default Media Sources
 * @var xPDO\xPDO $xpdo
 */
$collection[1]= $xpdo->newObject('MODX\Sources\modMediaSource');
$collection[1]->fromArray(array (
  'id' => 1,
  'name' => 'Filesystem',
  'description' => '',
  'class_key' => 'sources.modFileMediaSource',
  'properties' => array(),
), '', true, true);