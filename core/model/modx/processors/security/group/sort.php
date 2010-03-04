<?php
/**
 * Sort users and user groups, effectively repositioning users into proper
 * groups
 *
 * @param json $data The encoded tree data
 *
 * @package modx
 * @subpackage processors.security.group
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('user');

if (empty($scriptProperties['data'])) return $modx->error->failure();
$data = urldecode($scriptProperties['data']);
$data = $modx->fromJSON($data);

$groups = array();
getGroupsFormatted($groups,$data);

$users = array();
getUsersFormatted($users,$data);

/* readjust groups */
foreach ($groups as $ar_group) {
    if ($ar_group['id'] != 0) {
        $group = $modx->getObject('modUserGroup',$ar_group['id']);
        if ($group == null) continue;
        $old_parent_id = $group->get('parent');
    } else {
        $group = $modx->newObject('modUserGroup');
        $old_parent_id = 0;
    }

    if ($ar_group['parent'] == $group->get('id')) {
        return $modx->error->failure($modx->lexicon('err_self_parent'));
    }


    if ($ar_group['parent'] == 0 || $old_parent_id != $ar_group['parent']) {

        /* get new parent, if invalid, skip, unless is root */
        if ($ar_group['parent'] != 0) {
            $parent = $modx->getObject('modUserGroup',$ar_group['parent']);
            if ($parent == null) continue;
            $depth = $parent->get('depth') + 1;
        } else {
            $depth = 0;
        }

        /* save new parent and depth */
        $group->set('parent',$ar_group['parent']);
        $group->set('depth',$depth);
    }
    if ($ar_group['id'] != 0) $group->save();
}

/* readjust users */
foreach ($users as $ar_user) {
    $user = $modx->getObject('modUser',$ar_user['id']);
    if ($user == null) continue;

    /* get new parent, if invalid, skip, unless is root */
    if ($ar_user['parent'] != 0) {
        $ugm = $modx->getObject('modUserGroupMember',array(
            'user_group' => $ar_user['parent'],
            'member' => $user->get('id'),
        ));
        if ($ugm == null) {
            $ugm = $modx->newObject('modUserGroupMember');
            $ugm->set('user_group',$ar_user['parent']);
        }
        $ugm->set('member',$user->get('id'));
        $ugm->save();
    }
}



function getGroupsFormatted(&$ar_nodes,$cur_level,$parent = 0) {
    $order = 0;
    foreach ($cur_level as $id => $children) {
        $id = substr($id,2); /* get rid of CSS id n_ prefix */
        if (substr($id,0,2) == 'ug') {
            $ar_nodes[] = array(
                'id' => substr($id,3),
                'parent' => substr($parent,3),
                'order' => $order,
            );
            $order++;
        }
        getGroupsFormatted($ar_nodes,$children,$id);
    }
}

function getUsersFormatted(&$ar_nodes,$cur_level,$parent = 0) {
    $order = 0;
    foreach ($cur_level as $id => $children) {
        $id = substr($id,2); /* get rid of CSS id n_ prefix */
        if (substr($id,0,4) == 'user') {
            $ar_nodes[] = array(
                'id' => substr($id,5),
                'parent' => substr($parent,3),
                'order' => $order,
            );
            $order++;
        }
        getUsersFormatted($ar_nodes,$children,$id);
    }
}