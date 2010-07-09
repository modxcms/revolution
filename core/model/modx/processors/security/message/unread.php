<?php
/**
 * Mark a message as unread
 *
 * @param integer $id The ID of the message
 *
 * @package modx
 * @subpackage processors.security.message
 */
if (!$modx->hasPermission('messages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('messages');

$message = $modx->getObject('modUserMessage',$scriptProperties['id']);
if ($message == null) return $modx->error->failure($modx->lexicon('message_err_not_found'));

$message->set('read',false);

if ($message->save() === false) {
    return $modx->error->failure($modx->lexicon('message_err_save'));
}

return $modx->error->success();