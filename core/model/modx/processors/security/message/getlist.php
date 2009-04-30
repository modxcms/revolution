<?php
/**
 * Get a list of messages
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to date_sent.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 *
 * @package modx
 * @subpackage processors.security.message
 */
$modx->lexicon->load('messages','user');

if (!$modx->hasPermission('messages')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'date_sent';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'DESC';

$c = $modx->newQuery('modUserMessage');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$c->where(array('recipient' => $modx->user->get('id')));
$messages = $modx->getCollection('modUserMessage', $c);

$cc = $modx->newQuery('modUserMessage');
$count = $modx->getCount('modUserMessage',$cc);

$ms = array();
foreach ($messages as $message) {
	$ma = $message->toArray();
    $sender = $modx->getObject('modUser',$message->get('sender'));
    $ma['sender_name'] = $sender->get('username');
    $ma['read'] = $message->get('read') ? true : false;
    $ma['menu'] = array(
        array(
            'text' => $modx->lexicon('reply'),
            'handler' => array(
                'xtype' => 'window-message-reply'
                ,'id' => $message->get('id'),
            ),
        ),
        array(
            'text' => $modx->lexicon('forward'),
            'handler' => array(
                'xtype' => 'window-message-forward'
                ,'id' => $message->get('id'),
            ),
        ),
        array(
            'text' => $modx->lexicon('mark_unread'),
            'handler' => 'this.markUnread',
        ),
        '-',
        array(
            'text' => $modx->lexicon('delete'),
            'handler' => 'this.remove.createDelegate(this,["message_remove_confirm"])'
        ),
    );
	$ms[] = $ma;
}
return $this->outputArray($ms,$count);