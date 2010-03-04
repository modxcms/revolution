<?php
/**
 * Gets a role
 *
 * @param integer $id The ID of the role
 *
 * @package modx
 * @subpackage processors.security.role
 */
if (!$modx->hasPermission('view_role')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('role_err_ns'));
$role = $modx->getObject('modUserGroupRole',$scriptProperties['id']);
if ($role == null) return $modx->error->failure($modx->lexicon('role_err_nfs',array('role' => $scriptProperties['id'])));

return $modx->error->success('',$role);