<?php
/**
 * Create a message
 *
 * @param string $subject The subject of the message
 * @param string $message The body of the message
 * @param string $type The target of the message. Either user/role/usergroup/all
 * @param integer $role (optional)
 * @param integer $user (optional)
 * @param integer $group (optional)
 *
 * @package modx
 * @subpackage processors.security.message
 */
if (!$modx->hasPermission('messages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('messages','user');

$type = $modx->getOption('type',$scriptProperties,'user');

/* validation */
if (empty($scriptProperties['subject'])) {
    return $modx->error->failure($modx->lexicon('message_err_not_specified_subject'));
}

/* process message */
switch ($type) {
    case 'user':
        $userId = (integer)$scriptProperties['user'];
        if (strlen($userId) !== strlen($scriptProperties['user'])) {
            return $modx->error->failure($modx->lexicon('user_err_nf'));
        }

        $user = $modx->getObject('modUser', array('id' => $userId));
        if ($user == null) {
            return $modx->error->failure($modx->lexicon('user_err_nf'));
        }

        $message = $modx->newObject('modUserMessage');
        $message->set('subject',$scriptProperties['subject']);
        $message->set('message',$scriptProperties['message']);
        $message->set('sender',$modx->user->get('id'));
        $message->set('recipient',$user->get('id'));
        $message->set('private',true);
        $message->set('date_sent',time());
        $message->set('read',false);

        if ($message->save() === false) {
            return $modx->error->failure($modx->lexicon('message_err_save'));
        }
    break;

    case 'role':
        $roleId = (integer)$scriptProperties['role'];
        if (strlen($roleId) !== strlen($scriptProperties['role'])) {
            return $modx->error->failure($modx->lexicon('role_err_not_found'));
        }

        $role = $modx->getObject('modUserGroupRole', array('id' => $roleId));
        if ($role == null) {
            return $modx->error->failure($modx->lexicon('role_err_not_found'));
        }

        $users = $modx->getCollection('modUserGroupMember',array(
            'role' => $role->get('id'),
        ));

        foreach ($users as $user) {
            if ($user->get('internalKey') != $modx->user->get('id')) {
                $message = $modx->newObject('modUserMessage');
                $message->set('recipient',$user->get('internalKey'));
                $message->set('subject',$scriptProperties['subject']);
                $message->set('message',$scriptProperties['message']);
                $message->set('sender',$modx->user->get('id'));
                $message->set('date_sent',time());
                $message->set('private',false);
                if ($message->save() === false) {
                    return $modx->error->failure($modx->lexicon('message_err_save'));
                }
            }
        }
    break;
    case 'usergroup':
        $groupId = (integer)$scriptProperties['group'];
        if (strlen($groupId) !== strlen($scriptProperties['group'])) {
            return $modx->error->failure($modx->lexicon('group_err_not_found'));
        }

        $group = $modx->getObject('modUserGroup', array('id' => $groupId));
        if ($group == null) {
            return $modx->error->failure($modx->lexicon('group_err_not_found'));
        }

        $userGroupMembers = $modx->getCollection('modUserGroupMember',array(
            'user_group' => $group->get('id'),
        ));

        foreach ($userGroupMembers as $userGroupMember) {
            $message = $modx->newObject('modUserMessage');
            $message->set('recipient',$userGroupMember->get('member'));
            $message->set('subject',$scriptProperties['subject']);
            $message->set('message',$scriptProperties['message']);
            $message->set('sender',$modx->user->get('id'));
            $message->set('date_sent',time());
            $message->set('private',false);
            if ($message->save() === false) {
                return $modx->error->failure($modx->lexicon('message_err_save'));
            }
        }
    break;
    case 'all':
        $users = $modx->getCollection('modUser');
        foreach ($users as $user) {
            if ($user->get('id') != $modx->user->get('id')) {
                $message = $modx->newObject('modUserMessage');
                $message->set('recipient',$user->get('id'));
                $message->set('sender',$modx->user->get('id'));
                $message->set('subject',$scriptProperties['subject']);
                $message->set('message',$scriptProperties['message']);
                $message->set('date_sent',time());
                $message->set('private',false);
                if ($message->save() === false) {
                    return $modx->error->failure($modx->lexicon('message_err_save'));
                }
            }
        }
    break;
}

return $modx->error->success('',$message);
