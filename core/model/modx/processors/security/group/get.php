<?php
/**
 * Gets a user group
 *
 * @param integer $id The ID of the user group
 *
 * @package modx
 * @subpackage processors.security.group
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('user_group_err_not_specified'));

$ug = $modx->getObject('modUserGroup',$_REQUEST['id']);
if ($ug == null) return $modx->error->failure($modx->lexicon('user_group_err_not_found'));

if (isset($_REQUEST['getUsers']) && $_REQUEST['getUsers']) {
    $ugms = $modx->getCollection('modUserGroupMember',array(
        'user_group' => $ug->get('id'),
    ));

    $data = array();
    foreach ($ugms as $ugm) {
        $user = $ugm->getOne('modUser');
        $role = $ugm->getOne('modUserGroupRole');
        if ($user) {
            $role_name = $role != null ? $role->get('name') : '';
            $data[] = array(
                $user->get('id'),
                $user->get('username'),
                $ugm->get('role'),
                $role_name,
            );
        }
    }
    $ug->set('users','(' . $modx->toJSON($data) . ')');
}

return $modx->error->success('',$ug);