<?php
/**
 * Default Media Sources
 * @var xPDO $xpdo
 */

use MODX\Revolution\Sources\modMediaSource;

$collection[1]= $xpdo->newObject(modMediaSource::class);
$collection[1]->fromArray([
  'id' => 1,
  'name' => 'Filesystem',
  'description' => '',
  'class_key' => 'MODX\Revolution\Sources\modFileMediaSource',
  'properties' => [],
], '', true, true);
$collection[2]= $xpdo->newObject(modMediaSource::class);
$collection[2]->fromArray([
  'id' => 2,
  'name' => 'Assets',
  'description' => '',
  'class_key' => 'MODX\Revolution\Sources\modFileMediaSource',
  'properties' => [
    'basePath' => [
        'name' => 'basePath',
        'desc' => 'prop_file.basePath_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '[[++assets_url]]',
        'lexicon' => 'core:source',
    ],
    'baseUrl' => [
        'name' => 'baseUrl',
        'desc' => 'prop_file.baseUrl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '[[++assets_url]]',
        'lexicon' => 'core:source',
    ],
  ],
], '', true, true);
