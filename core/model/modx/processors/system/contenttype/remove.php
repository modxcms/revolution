<?php
/**
 * Removes a content type
 *
 * @param integer $id The ID of the content type
 *
 * @package modx
 * @subpackage processors.system.contenttype
 */
if (!$modx->hasPermission('content_types')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('content_type');

/* get content type */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('content_type_err_ns'));
$contentType = $modx->getObject('modContentType',$scriptProperties['id']);
if ($contentType == null) {
    return $modx->error->failure($modx->lexicon('content_type_err_nfs',array('id' => $scriptProperties['id'])));
}

/* remove content type */
if ($contentType->remove() == false) {
    return $modx->error->failure($modx->lexicon('content_type_err_remove'));
}

/* log manager action */
$modx->logManagerAction('content_type_delete','modContentType',$contentType->get('id'));

return $modx->error->success('',$contentType);