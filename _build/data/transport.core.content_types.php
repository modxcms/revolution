<?php
$collection['1']= $xpdo->newObject('modContentType');
$collection['1']->fromArray(array (
  'id' => 1,
  'name' => 'HTML',
  'description' => 'HTML content',
  'mime_type' => 'text/html',
  'file_extensions' => '.html',
  'headers' => 'NULL',
  'binary' => 0,
), '', true, true);
$collection['2']= $xpdo->newObject('modContentType');
$collection['2']->fromArray(array (
  'id' => 2,
  'name' => 'XML',
  'description' => 'XML content',
  'mime_type' => 'text/xml',
  'file_extensions' => '.xml',
  'headers' => 'NULL',
  'binary' => 0,
), '', true, true);
$collection['3']= $xpdo->newObject('modContentType');
$collection['3']->fromArray(array (
  'id' => 3,
  'name' => 'text',
  'description' => 'plain text content',
  'mime_type' => 'text/plain',
  'file_extensions' => '.txt',
  'headers' => 'NULL',
  'binary' => 0,
), '', true, true);
$collection['4']= $xpdo->newObject('modContentType');
$collection['4']->fromArray(array (
  'id' => 4,
  'name' => 'CSS',
  'description' => 'CSS content',
  'mime_type' => 'text/css',
  'file_extensions' => '.css',
  'headers' => 'NULL',
  'binary' => 0,
), '', true, true);
$collection['5']= $xpdo->newObject('modContentType');
$collection['5']->fromArray(array (
  'id' => 5,
  'name' => 'javascript',
  'description' => 'javascript content',
  'mime_type' => 'text/javascript',
  'file_extensions' => '.js',
  'headers' => 'NULL',
  'binary' => 0,
), '', true, true);
$collection['6']= $xpdo->newObject('modContentType');
$collection['6']->fromArray(array (
  'id' => 6,
  'name' => 'RSS',
  'description' => 'For RSS feeds',
  'mime_type' => 'application/rss+xml',
  'file_extensions' => '.rss',
  'headers' => 'NULL',
  'binary' => 0,
), '', true, true);