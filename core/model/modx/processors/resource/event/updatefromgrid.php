<?php
/**
 * Update a resource from the site schedule grid.
 *
 * @param json $data A JSON array of data to update with.
 *
 * @package modx
 * @subpackage processors.resource.event
 */
$modx->lexicon->load('resource');

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['id'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));
$resource = $modx->getObject($_DATA['class_key'],$_DATA['id']);
if ($resource == null) return $modx->error->failure($modx->lexicon('resource_err_nf'));

if ($_DATA['pub_date'] != '')
    $_DATA['pub_date'] = strftime('%Y-%m-%d',strtotime($_DATA['pub_date']));

if ($_DATA['unpub_date'] != '')
    $_DATA['unpub_date'] = strftime('%Y-%m-%d',strtotime($_DATA['unpub_date']));

$resource->fromArray($_DATA);

if ($resource->save() === false) {
    return $modx->error->failure($modx->lexicon('resource_err_save'));
}

return $modx->error->success();