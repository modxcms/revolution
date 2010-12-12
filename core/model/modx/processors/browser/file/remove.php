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

/* get working context */
$wctx = isset($scriptProperties['wctx']) && !empty($scriptProperties['wctx']) ? $scriptProperties['wctx'] : '';
if (!empty($wctx)) {
    $workingContext = $modx->getContext($wctx);
    if (!$workingContext) {
        return $modx->error->failure($modx->error->failure($modx->lexicon('permission_denied')));
    }
} else {
    $workingContext =& $modx->context;
}

$modx->getService('fileHandler','modFileHandler', '', array('context' => $workingContext->get('key')));

/* in case rootVisible is true */
$file = str_replace('root/','',$scriptProperties['file']);
$file = str_replace('undefined/','',$file);

/* create modFile object */
$root = $modx->fileHandler->getBasePath(false);
$file = $modx->fileHandler->make($root.$file);

/* verify file exists and is writable */
if (!$file->exists()) {
    return $modx->error->failure($modx->lexicon('file_err_nf').': '.$file->getPath());
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

return $modx->error->success('',array(
    'path' => $file->getPath(),
));
