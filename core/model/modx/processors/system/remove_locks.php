<?php
/**
 * Removes locks on all objects
 *
 * @package modx
 * @subpackage processors.system
 */
if (!$modx->hasPermission('remove_locks')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* remove locks */
$locks = $modx->getCollection('modActiveUser');
foreach ($locks as $lock) {
	if (!$lock->remove()) return $modx->error->failure($modx->lexicon('remove_locks_error'));
}

return $modx->error->success();