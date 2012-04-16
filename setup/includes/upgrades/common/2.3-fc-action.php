<?php
/**
 * Common upgrade script for 2.3 modAction deprecation and its effects on FC rules
 *
 * @var modX $modx
 * @package setup
 */
/** @var modAction $createAction */
$createAction = $modx->getObject('modAction',array('controller' => 'resource/create'));
if ($createAction) {
    $modx->updateCollection('modActionDom',array(
        'action' => 'resource/create',
    ),array(
        'action' => $createAction->get('id'),
    ));
    $modx->updateCollection('modFormCustomizationSet',array(
        'action' => 'resource/create',
    ),array(
        'action' => $createAction->get('id'),
    ));
    $modx->updateCollection('modActionField',array(
        'action' => 'resource/create',
    ),array(
        'action' => $createAction->get('id'),
    ));
}
/** @var modAction $updateAction */
$updateAction = $modx->getObject('modAction',array('controller' => 'resource/update'));
if ($updateAction) {
    $modx->updateCollection('modActionDom',array(
        'action' => 'resource/update',
    ),array(
        'action' => $updateAction->get('id'),
    ));
    $modx->updateCollection('modFormCustomizationSet',array(
        'action' => 'resource/update',
    ),array(
        'action' => $updateAction->get('id'),
    ));
    $modx->updateCollection('modActionField',array(
        'action' => 'resource/update',
    ),array(
        'action' => $updateAction->get('id'),
    ));
}