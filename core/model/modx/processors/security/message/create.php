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
$modx->lexicon->load('messages','user');

if (!$modx->hasPermission('messages')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* validation */
if (!isset($_POST['subject']) || $_POST['subject'] == '') {
	return $modx->error->failure($modx->lexicon('message_err_not_specified_subject'));
}

/* process message */
switch ($_POST['type']) {
	case 'user':
		$user = $modx->getObject('modUser',$_POST['user']);
		if ($user == null) {
		    return $modx->error->failure($modx->lexicon('user_err_not_found'));
        }

		$message = $modx->newObject('modUserMessage');
		$message->set('subject',$_POST['subject']);
		$message->set('message',$_POST['message']);
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
		$role = $modx->getObject('modUserGroupRole',$_POST['role']);
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
				$message->set('subject',$_POST['subject']);
				$message->set('message',$_POST['message']);
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
        $group = $modx->getObject('modUserGroup',$_POST['group']);
        if ($group == null) {
            return $modx->error->failure($modx->lexicon('group_err_not_found'));
        }

        $users = $modx->getCollection('modUserGroupMember',array(
            'user_group' => $group->get('id'),
        ));

        foreach ($users as $user) {
            if ($user->get('internalKey') != $modx->user->get('id')) {
                $message = $modx->newObject('modUserMessage');
                $message->set('recipient',$user->get('internalKey'));
                $message->set('subject',$_POST['subject']);
                $message->set('message',$_POST['message']);
                $message->set('sender',$modx->user->get('id'));
                $message->set('date_sent',time());
                $message->set('private',false);
                if ($message->save() === false) {
                    return $modx->error->failure($modx->lexicon('message_err_save'));
                }
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
				$message->set('subject',$_POST['subject']);
				$message->set('message',$_POST['message']);
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