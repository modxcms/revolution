<?php
/**
 * Reply to a message
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

/* validation */
if (empty($scriptProperties['subject'])) {
	$modx->error->addField('m_reply_subject',$modx->lexicon('message_err_not_specified_subject'));
}

$fs = $modx->error->getFields();
$fields = '<ul>';
foreach ($fs as $f)
	$fields .= '<li>'.ucwords(str_replace('_',' ',$f)).'</li>';
$fields .= '</ul>';

if ($modx->error->hasError()) return $modx->error->failure($modx->lexicon('validation_system_settings').$fields);

/* process message */
switch ($scriptProperties['type']) {
	case 'user':
		$user = $modx->getObject('modUser',$scriptProperties['user']);
		if ($user == null) return $modx->error->failure($modx->lexicon('user_err_not_found'));

		$message = $modx->newObject('modUserMessage');
		$message->set('type','Message');
		$message->set('subject',$scriptProperties['subject']);
		$message->set('message',$scriptProperties['message']);
		$message->set('sender',$modx->user->get('id'));
		$message->set('recipient',$user->get('id'));
		$message->set('private',true);
		$message->set('postdate',time());
		$message->set('read',false);

		if (!$message->save()) return $modx->error->failure($modx->lexicon('message_err_save'));
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
				$message->set('postdate',time());
				$message->set('type','Message');
				$message->set('private',false);
				if (!$message->save()) return $modx->error->failure($modx->lexicon('message_err_save'));
			}
		}
		break;
}
return $modx->error->success();