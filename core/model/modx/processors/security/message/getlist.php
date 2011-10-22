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
if (!$modx->hasPermission('messages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('messages','user');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'date_sent');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');
$search = $modx->getOption('search',$scriptProperties,'');

/* build query */
$c = $modx->newQuery('modUserMessage');
$c->innerJoin('modUser','Sender');
$c->where(array(
    'recipient' => $modx->user->get('id')
));
if (!empty($search)) {
    $c->andCondition(array(
        'subject:LIKE' => '%'.$search.'%',
        'OR:message:LIKE' => '%'.$search.'%',
    ),null,2);
}
$count = $modx->getCount('modUserMessage',$c);
$c->select(array(
    'modUserMessage.*',
    'sender_username' => 'Sender.username',
));
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$messages = $modx->getCollection('modUserMessage',$c);

/* iterate */
$list = array();
foreach ($messages as $message) {
	$ma = $message->toArray();
    $ma['sender_name'] = $message->get('sender_username');
    $ma['read'] = $message->get('read') ? true : false;
    $list[] = $ma;
}
return $this->outputArray($list,$count);