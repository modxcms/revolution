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
$modx->lexicon->load('content_type');

if (!$modx->hasPermission('content_types')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('content_type_err_ns'));
$contenttype = $modx->getObject('modContentType',$_POST['id']);
if ($contenttype == null) {
    return $modx->error->failure($modx->lexicon('content_type_err_nfs',array(
        'id' => $_POST['id'],
    )));
}

$contenttype->fromArray($_POST);
if ($contenttype->save() == false) {
    $modx->error->checkValidation($contenttype);
    return $modx->error->failure($modx->lexicon('content_type_err_save'));
}

/* log manager action */
$modx->logManagerAction('content_type_save','modContentType',$contenttype->get('id'));

return $modx->error->success();