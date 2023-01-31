<?php

/**
 * Scripts removes deprecated fields from the main resource table
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modResource;

$class = modResource::class;
$table = $modx->getTableName($class);
$sql = "ALTER TABLE {$modx->escape($table)}
        DROP COLUMN `privateweb`,
        DROP COLUMN `privatemgr`,
        DROP COLUMN `donthit`;";
$modx->exec($sql);
