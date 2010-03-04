<?php
/**
 * Remove a user group
 *
 * @param integer $id The ID of the user group
 *
 * @package modx
 * @subpackage processors.security.group
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

/* get usergroup */
if (!isset($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('user_group_err_not_specified'));
$usergroup = $modx->getObject('modUserGroup',$scriptProperties['id']);
if ($usergroup == null) return $modx->error->failure($modx->lexicon('user_group_err_not_found'));

/* make sure cannot remove administrator group */
if ($usergroup->get('id') == 1 || $usergroup->get('name') == $modx->lexicon('administrator')) {
    return $modx->error->failure($modx->lexicon('user_group_err_remove_admin'));
}

/* remove usergroup */
if ($usergroup->remove() == false) {
    return $modx->error->failure($modx->lexicon('user_group_err_remove'));
}

/* log manager action */
$modx->logManagerAction('delete_user_group','modUserGroup',$usergroup->get('id'));

return $modx->error->success();