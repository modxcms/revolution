<?php
/**
 * Default Media Sources
 * @var xPDO $xpdo
 */
$collection[1]= $xpdo->newObject('sources.modMediaSource');
$collection[1]->fromArray(array (
  'id' => 1,
  'name' => 'Filesystem',
  'description' => '',
  'class_key' => 'sources.modFileMediaSource',
  'basePath' => '',
  'basePathRelative' => true,
  'baseUrl' => '',
  'baseUrlRelative' => true,
  'properties' => array(),
), '', true, true);