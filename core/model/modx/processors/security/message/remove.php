<?php
/**
 * Remove a message
 *
 * @param integer $id The ID of the message
 *
 * @package modx
 * @subpackage processors.security.message
 */
if (!$modx->hasPermission('messages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('messages','user');

/* get message */
$message = $modx->getObject('modUserMessage',$scriptProperties['id']);
if ($message == null) {
    return $modx->error->failure($modx->lexicon('message_err_not_found'));
}

/* make sure user is message recipient */
if ($message->get('recipient') != $modx->user->get('id')) {
	return $modx->error->failure($modx->lexicon('message_err_remove_notauth'));
}

/* delete message */
if ($message->remove() === false) {
    return $modx->error->failure($modx->lexicon('message_err_remove'));
}

return $modx->error->success();