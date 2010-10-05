<?php
/**
 * Default Class Map types
 */
$collection[1]= $xpdo->newObject('modClassMap');
$collection[1]->fromArray(array (
  'class' => 'modDocument',
  'parent_class' => 'modResource',
  'name_field' => 'pagetitle',
), '', true, true);

$collection[2]= $xpdo->newObject('modClassMap');
$collection[2]->fromArray(array (
  'class' => 'modWebLink',
  'parent_class' => 'modResource',
  'name_field' => 'pagetitle',
), '', true, true);

$collection[3]= $xpdo->newObject('modClassMap');
$collection[3]->fromArray(array (
  'class' => 'modSymLink',
  'parent_class' => 'modResource',
  'name_field' => 'pagetitle',
), '', true, true);

$collection[4]= $xpdo->newObject('modClassMap');
$collection[4]->fromArray(array (
  'class' => 'modStaticResource',
  'parent_class' => 'modResource',
  'name_field' => 'pagetitle',
), '', true, true);

$collection[5]= $xpdo->newObject('modClassMap');
$collection[5]->fromArray(array (
  'class' => 'modTemplate',
  'parent_class' => 'modElement',
  'name_field' => 'templatename',
), '', true, true);

$collection[6]= $xpdo->newObject('modClassMap');
$collection[6]->fromArray(array (
  'class' => 'modTemplateVar',
  'parent_class' => 'modElement',
  'name_field' => 'name',
), '', true, true);

$collection[7]= $xpdo->newObject('modClassMap');
$collection[7]->fromArray(array (
  'class' => 'modChunk',
  'parent_class' => 'modElement',
  'name_field' => 'name',
), '', true, true);

$collection[8]= $xpdo->newObject('modClassMap');
$collection[8]->fromArray(array (
  'class' => 'modSnippet',
  'parent_class' => 'modElement',
  'name_field' => 'name',
), '', true, true);

$collection[9]= $xpdo->newObject('modClassMap');
$collection[9]->fromArray(array (
  'class' => 'modPlugin',
  'parent_class' => 'modElement',
  'name_field' => 'name',
), '', true, true);