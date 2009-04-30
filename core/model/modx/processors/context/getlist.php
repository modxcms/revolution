<?php
/**
 * Grabs a list of contexts.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @param string $sort (optional) The column to sort by. Defaults to key.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.context
 */
$modx->lexicon->load('context');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'key';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';
if ($_REQUEST['sort'] == 'key_link') $_REQUEST['sort'] = 'key';

$c = $modx->newQuery('modContext');
$c->select($modx->getSelectColumns('modContext'));
$c->sortby('`' . $_REQUEST['sort'] . '`', $_REQUEST['dir']);
$c->limit($_REQUEST['limit'], $_REQUEST['start']);

$collection = $modx->getCollection('modContext', $c);
$actions = $modx->request->getAllActionIDs();

$list = array();
foreach ($collection as $key => $object) {
    if (!$object->checkPolicy('list')) continue;
	$la = array_merge(
       $object->toArray(),
       array('key_link' => '<a href="index.php?a='.$actions['context/update'].'&key='.$key.'" title="' . $modx->lexicon('click_to_edit_title') . '">' . $key . '</a>')
    );
    $la['menu'] = array(
        array(
            'text' => $modx->lexicon('context_update'),
            'handler' => 'this.update'
        ),
    );
    if (!in_array($key,array('mgr','web'))) {
        array_push($la['menu'],
            '-',
            array(
                'text' => $modx->lexicon('context_remove'),
                'handler' => 'this.remove.createDelegate(this,["context_remove_confirm"])',
            )
        );
    }
    $list[]= $la;
}
return $this->outputArray($list);