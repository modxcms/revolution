<?php
/**
 * Removes a file.
 *
 * @param string $file The name of the file.
 * @param boolean $prependPath If true, will prepend the rb_base_dir to the file
 * name.
 *
 * @package modx
 * @subpackage processors.browser.file
 */
if (!$modx->hasPermission('file_remove')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

if (empty($scriptProperties['file'])) return $modx->error->failure($modx->lexicon('file_err_ns'));

/* get base paths and sanitize incoming paths */
$modx->getService('fileHandler','modFileHandler');

/* in case rootVisible is true */
$file = str_replace('root/','',$scriptProperties['file']);
$file = str_replace('undefined/','',$file);

/* create modFile object */
$root = $modx->fileHandler->getBasePath();
$file = $modx->fileHandler->make($root.$file);

/* verify file exists and is writable */
if (!$file->exists()) {
    return $modx->error->failure($modx->lexicon('file_err_nf').': '.$file);
} else if (!$file->isReadable() || !$file->isWritable()) {
    return $modx->error->failure($modx->lexicon('file_err_perms_remove'));
} else if (!($file instanceof modFile)) {
    return $modx->error->failure($modx->lexicon('file_err_invalid'));
}

/* remove file */
if (!$file->remove()) {
    return $modx->error->failure($modx->lexicon('file_err_remove'));
}

/* log manager action */
$modx->logManagerAction('file_remove','',$file->getPath());

return $modx->error->success();