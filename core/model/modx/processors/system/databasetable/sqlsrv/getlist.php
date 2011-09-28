<?php
/**
 * Gets a list of database tables
 *
 * SqlSrv-specific queries and results
 *
 * @package modx
 * @subpackage processors.system.databasetable
 */
if (!$modx->hasPermission('database')) return $modx->error->failure($modx->lexicon('permission_denied'));
