<?php
/**
 * Flushes permissions for the logged in user.
 *
 * @package modx
 * @subpackage processors.security.access
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if ($modx->getUser()) {
    $modx->user->getAttributes(array(), '', true);
}
return $modx->error->success();