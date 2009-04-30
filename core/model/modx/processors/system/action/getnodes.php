<?php
/**
 * Grabs the actions in node format
 *
 * @param string $id The parent node ID
 *
 * @package modx
 * @subpackage processors.system.action
 */
$modx->lexicon->load('action','menu');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['id'])) $_REQUEST['id'] = 'n_0';

$ar = explode('_',$_REQUEST['id']);
$type = $ar[1];
$id = $ar[2];


/* contexts */
if ($type == 'root') {
    $namespaces = $modx->getCollection('modNamespace');

    $cs = array();
    foreach ($namespaces as $namespace) {
        $cs[] = array(
            'text' => $namespace->get('name'),
            'id' => 'n_namespace_'.$namespace->get('name'),
            'leaf' => false,
            'cls' => 'folder',
            'type' => 'namespace',
            'menu' => array( 'items' => array(
                array(
                    'text' => $modx->lexicon('action_create_here'),
                    'handler' => 'function(itm,e) {
                        this.create(itm,e);
                    }',
                ),
            )),
        );
    }

    return $this->toJSON($cs);
    die();

/* root actions */
} else if ($type == 'namespace') {
    $c = $modx->newQuery('modAction');
    $c->where(array(
        'parent' => 0,
        'namespace' => $id,
    ));
    $c->sortby('controller','ASC');
    /*$c->limit($_REQUEST['limit'],$_REQUEST['start']);*/

    $actions = $modx->getCollection('modAction',$c);

    $cc = $modx->newQuery('modAction');
    $cc->where(array(
        'parent' => 0,
        'namespace' => $id,
    ));
    $count = $modx->getCount('modAction',$cc);

    $as = array();
    foreach ($actions as $action) {
        $as[] = array(
            'text' => $action->get('controller').' ('.$action->get('id').')',
            'id' => 'n_action_'.$action->get('id'),
            'leaf' => false,
            'cls' => 'action',
            'type' => 'action',
            'menu' => array(
                'items' => array(
                    array(
                        'text' => $modx->lexicon('action_update'),
                        'handler' => 'function(itm,e) {
                            this.update(itm,e);
                        }',
                    ),'-',array(
                        'text' => $modx->lexicon('action_create_here'),
                        'handler' => 'function(itm,e) {
                            this.create(itm,e);
                        }',
                    ),'-',array(
                        'text' => $modx->lexicon('action_remove'),
                        'handler' => 'function(itm,e) {
                            this.remove(itm,e);
                        }',
                    ),
                ),
            ),
        );
    }

    return $this->toJSON($as);
    die();

/* subactions */
} else {
    $c = $modx->newQuery('modAction');
    $c->where(array(
        'parent' => $id,
    ));
    $c->sortby('controller','ASC');
    /*$c->limit($_REQUEST['limit'],$_REQUEST['start']);*/

    $actions = $modx->getCollection('modAction',$c);
    $cc = $modx->newQuery('modAction');
    $cc->where(array(
        'parent' => $id,
    ));
    $count = $modx->getCount('modAction',$cc);

    $as = array();
    foreach ($actions as $action) {
        $as[] = array(
            'text' => $action->get('controller').' ('.$action->get('id').')',
            'id' => 'n_action_'.$action->get('id'),
            'leaf' => 0,
            'cls' => 'action',
            'type' => 'action',
            'menu' => array(
                'items' => array(
                    array(
                        'text' => $modx->lexicon('action_update'),
                        'handler' => 'function(itm,e) {
                            this.update(itm,e);
                        }',
                    ),'-',array(
                        'text' => $modx->lexicon('action_create_here'),
                        'handler' => 'function(itm,e) {
                            this.create(itm,e);
                        }',
                    ),'-',array(
                        'text' => $modx->lexicon('action_remove'),
                        'handler' => 'function(itm,e) {
                            this.remove(itm,e);
                        }',
                    ),
                ),
            ),
        );
    }

    return $this->toJSON($as);
    die();
}
