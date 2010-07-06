<?php
/**
 * Update a content type from the grid. Sent through JSON-encoded 'data'
 * parameter.
 *
 * @param integer $id The ID of the content type
 * @param string $name The new name
 * @param string $description (optional) A short description
 * @param string $mime_type The MIME type for the content type
 * @param string $file_extensions A list of file extensions associated with this
 * type
 * @param string $headers Any headers to be sent with resources with this type
 * @param boolean $binary If true, will be sent as binary data
 *
 * @package modx
 * @subpackage processors.system.contenttype
 */
if (!$modx->hasPermission('content_types')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('content_type');

/* loop through content types */
if (empty($scriptProperties['data'])) return $modx->error->failure();
$_DATA = $modx->fromJSON($scriptProperties['data']);

foreach ($_DATA as $ct) {
    /* get content type */
    if (empty($ct['id'])) continue;
    $contentType = $modx->getObject('modContentType',$ct['id']);
    if ($contentType == null) continue;

    /* save content type */
    $ct['binary'] = !empty($ct['binary']) ? true : false;
    $contentType->fromArray($ct);
    if ($contentType->save() == false) {
        $modx->error->checkValidation($contentType);
        return $modx->error->failure($modx->lexicon('content_type_err_save'));
    }

    /* log manager action */
    $modx->logManagerAction('content_type_save','modContentType',$contentType->get('id'));
}

return $modx->error->success('',$contentType);