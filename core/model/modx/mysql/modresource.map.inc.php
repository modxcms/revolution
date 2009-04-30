<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modResource']= array (
  'package' => 'modx',
  'table' => 'site_content',
  'fields' => 
  array (
    'type' => 'document',
    'contentType' => 'text/html',
    'pagetitle' => '',
    'longtitle' => '',
    'description' => '',
    'alias' => '',
    'link_attributes' => '',
    'published' => 0,
    'pub_date' => 0,
    'unpub_date' => 0,
    'parent' => 0,
    'isfolder' => 0,
    'introtext' => NULL,
    'content' => NULL,
    'richtext' => 1,
    'template' => 0,
    'menuindex' => 0,
    'searchable' => 1,
    'cacheable' => 1,
    'createdby' => 0,
    'createdon' => 0,
    'editedby' => 0,
    'editedon' => 0,
    'deleted' => 0,
    'deletedon' => 0,
    'deletedby' => 0,
    'publishedon' => 0,
    'publishedby' => 0,
    'menutitle' => '',
    'donthit' => 0,
    'haskeywords' => 0,
    'hasmetatags' => 0,
    'privateweb' => 0,
    'privatemgr' => 0,
    'content_dispo' => 0,
    'hidemenu' => 0,
    'class_key' => 'modDocument',
    'context_key' => '',
    'content_type' => 1,
  ),
  'fieldMeta' => 
  array (
    'type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => false,
      'default' => 'document',
    ),
    'contentType' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => 'text/html',
    ),
    'pagetitle' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'fulltext',
      'indexgrp' => 'content_ft_idx',
    ),
    'longtitle' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'fulltext',
      'indexgrp' => 'content_ft_idx',
    ),
    'description' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'fulltext',
      'indexgrp' => 'content_ft_idx',
    ),
    'alias' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
      'index' => 'index',
    ),
    'link_attributes' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'published' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'pub_date' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'unpub_date' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'parent' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'isfolder' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'introtext' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'index' => 'fulltext',
      'indexgrp' => 'content_ft_idx',
    ),
    'content' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'index' => 'fulltext',
      'indexgrp' => 'content_ft_idx',
    ),
    'richtext' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 1,
    ),
    'template' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'menuindex' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'searchable' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 1,
      'index' => 'index',
    ),
    'cacheable' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 1,
      'index' => 'index',
    ),
    'createdby' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'createdon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'editedby' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'editedon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'deleted' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
    'deletedon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'deletedby' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'publishedon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'publishedby' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'menutitle' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'donthit' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
    'haskeywords' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
    'hasmetatags' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
    'privateweb' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
    'privatemgr' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
    'content_dispo' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'hidemenu' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'class_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'modDocument',
      'index' => 'index',
    ),
    'context_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'content_type' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
  ),
  'aggregates' => 
  array (
    'Parent' => 
    array (
      'class' => 'modResource',
      'key' => 'parent',
      'local' => 'parent',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Children' => 
    array (
      'class' => 'modResource',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'parent',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modTemplate' => 
    array (
      'class' => 'modTemplate',
      'key' => 'id',
      'local' => 'template',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'CreatedBy' => 
    array (
      'class' => 'modUser',
      'key' => 'createdby',
      'local' => 'createdby',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'EditedBy' => 
    array (
      'class' => 'modUser',
      'key' => 'editedby',
      'local' => 'editedby',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'DeletedBy' => 
    array (
      'class' => 'modUser',
      'key' => 'deletedby',
      'local' => 'deletedby',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'PublishedBy' => 
    array (
      'class' => 'modUser',
      'key' => 'publishedby',
      'local' => 'publishedby',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modTemplateVar' => 
    array (
      'class' => 'modTemplateVar',
      'key' => 'id',
      'local' => 'id:template',
      'foreign' => 'contentid:templateid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modTemplateVarTemplate' => 
    array (
      'class' => 'modTemplateVarTemplate',
      'key' => 'template',
      'local' => 'template',
      'foreign' => 'templateid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'ContentType' => 
    array (
      'class' => 'modContentType',
      'local' => 'content_type',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
    'Context' => 
    array (
      'class' => 'modContext',
      'local' => 'context_key',
      'foreign' => 'key',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
  'composites' => 
  array (
    'modTemplateVarResource' => 
    array (
      'class' => 'modTemplateVarResource',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'contentid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modResourceGroupResource' => 
    array (
      'class' => 'modResourceGroupResource',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'document',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modResourceKeyword' => 
    array (
      'class' => 'modResourceKeyword',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'content_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modResourceMetatag' => 
    array (
      'class' => 'modResourceMetatag',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'content_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Acls' => 
    array (
      'class' => 'modAccessResource',
      'local' => 'id',
      'foreign' => 'target',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modResource']['aggregates']= array_merge($xpdo_meta_map['modResource']['aggregates'], array_change_key_case($xpdo_meta_map['modResource']['aggregates']));
if (XPDO_PHP4_MODE) $xpdo_meta_map['modResource']['composites']= array_merge($xpdo_meta_map['modResource']['composites'], array_change_key_case($xpdo_meta_map['modResource']['composites']));
$xpdo_meta_map['modresource']= & $xpdo_meta_map['modResource'];
