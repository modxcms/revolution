<?php
use MODX\Revolution\modContentType;

$collection['1']= $xpdo->newObject(modContentType::class);
$collection['1']->fromArray([
  'id' => 1,
  'name' => 'HTML',
  'description' => 'HTML content',
  'mime_type' => 'text/html',
  'file_extensions' => '.html',
  'icon' => '',
  'headers' => 'NULL',
  'binary' => 0,
], '', true, true);
$collection['2']= $xpdo->newObject(modContentType::class);
$collection['2']->fromArray([
  'id' => 2,
  'name' => 'XML',
  'description' => 'XML content',
  'mime_type' => 'text/xml',
  'file_extensions' => '.xml',
  'icon' => 'icon-xml',
  'headers' => 'NULL',
  'binary' => 0,
], '', true, true);
$collection['3']= $xpdo->newObject(modContentType::class);
$collection['3']->fromArray([
  'id' => 3,
  'name' => 'Text',
  'description' => 'Plain text content',
  'mime_type' => 'text/plain',
  'file_extensions' => '.txt',
  'icon' => 'icon-txt',
  'headers' => 'NULL',
  'binary' => 0,
], '', true, true);
$collection['4']= $xpdo->newObject(modContentType::class);
$collection['4']->fromArray([
  'id' => 4,
  'name' => 'CSS',
  'description' => 'CSS content',
  'mime_type' => 'text/css',
  'file_extensions' => '.css',
  'icon' => 'icon-css',
  'headers' => 'NULL',
  'binary' => 0,
], '', true, true);
$collection['5']= $xpdo->newObject(modContentType::class);
$collection['5']->fromArray([
  'id' => 5,
  'name' => 'JavaScript',
  'description' => 'JavaScript content',
  'mime_type' => 'text/javascript',
  'file_extensions' => '.js',
  'icon' => 'icon-js',
  'headers' => 'NULL',
  'binary' => 0,
], '', true, true);
$collection['6']= $xpdo->newObject(modContentType::class);
$collection['6']->fromArray([
  'id' => 6,
  'name' => 'RSS',
  'description' => 'For RSS feeds',
  'mime_type' => 'application/rss+xml',
  'file_extensions' => '.rss',
  'icon' => 'icon-rss',
  'headers' => 'NULL',
  'binary' => 0,
], '', true, true);
$collection['7']= $xpdo->newObject(modContentType::class);
$collection['7']->fromArray([
  'id' => 7,
  'name' => 'JSON',
  'description' => 'JSON',
  'mime_type' => 'application/json',
  'file_extensions' => '.json',
  'icon' => 'icon-json',
  'headers' => 'NULL',
  'binary' => 0,
], '', true, true);
$collection['8']= $xpdo->newObject(modContentType::class);
$collection['8']->fromArray([
  'id' => 8,
  'name' => 'PDF',
  'description' => 'PDF Files',
  'mime_type' => 'application/pdf',
  'file_extensions' => '.pdf',
  'icon' => 'icon-pdf',
  'headers' => 'NULL',
  'binary' => 1,
], '', true, true);
