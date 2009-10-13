<?php
/**
 * Flush all sessions
 *
 * @package modx
 * @subpackage processors.security
 */
if (!$modx->hasPermission('flush_sessions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if ($modx->getOption('session_handler_class',null,'modSessionHandler') == 'modSessionHandler') {
    $sessionTable = $modx->getTableName('modSession');

    if ($modx->query("TRUNCATE {$sessionTable}") == false) {
        return $modx->error->failure($modx->lexicon('flush_sessions_err'));
    }

    $modx->user->endSession();

} else {
    return $modx->error->failure($modx->lexicon('flush_sessions_not_supported'));
}
return $modx->error->success();