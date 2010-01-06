<?php
/**
 * Clears the manager log actions
 *
 * @package modx
 * @subpackage processors.system.log
 */
if (!$modx->hasPermission('logs')) return $modx->error->failure($modx->lexicon('permission_denied'));

$modx->exec("TRUNCATE {$modx->getTableName('modManagerLog')}");

return $modx->error->success();