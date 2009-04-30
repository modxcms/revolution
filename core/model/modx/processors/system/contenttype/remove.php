<?php
/**
 * Removes a content type
 *
 * @param integer $id The ID of the content type
 *
 * @package modx
 * @subpackage processors.system.contenttype
 */
$modx->lexicon->load('content_type');

if (!$modx->hasPermission('content_types')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('content_type_err_ns'));
$ct = $modx->getObject('modContentType',$_POST['id']);
if ($ct == null) {
    return $modx->error->failure(sprintf($modx->lexicon('content_type_err_nfs'),$_POST['id']));
}

if ($ct->remove() == false) {
    return $modx->error->failure($modx->lexicon('content_type_err_remove'));
}

/* log manager action */
$modx->logManagerAction('content_type_delete','modContentType',$ct->get('id'));

return $modx->error->success('',$ct);