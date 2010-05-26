<?php
/**
 * Activate multiple users
 *
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission('delete_user')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

if (empty($scriptProperties['users'])) {
    return $modx->error->failure($modx->lexicon('user_err_ns'));
}

$userIds = explode(',',$scriptProperties['users']);

foreach ($userIds as $userId) {
    $user = $modx->getObject('modUser',$userId);
    if ($user == null) continue;

    if ($user->remove() === false) {
        return $modx->error->failure($modx->lexicon('user_err_remove'));
    }
}

return $modx->error->success();
