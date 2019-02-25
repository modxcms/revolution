<?php
/**
 * Changes type of gender field to `tinyint` instead of `int` as this field shouldn't contain a lot of information.
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */
$class = 'modUserProfile';
$table = $modx->getTableName($class);

$description = $this->install->lexicon(
    'modify_column', [
        'column' => 'gender',
        'old' => 'int',
        'new' => 'tinyint',
        'table' => $table
    ]
);
$this->processResults($class, $description, [$modx->manager, 'alterField'], [$class, 'gender']);
