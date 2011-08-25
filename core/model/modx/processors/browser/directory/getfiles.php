<?php
/**
 * Gets all files in a directory
 *
 * @param string $dir The directory to browse
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 * @param boolean $prependUrl (optional) If true, will prepend rb_base_url to
 * the final url
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
if (!$modx->hasPermission('file_list')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

$dir = !isset($scriptProperties['dir']) || $scriptProperties['dir'] == 'root' ? '' : $scriptProperties['dir'];

/** @var modMediaSource|modFileMediaSource $source */
$modx->loadClass('sources.modMediaSource');
$source = modMediaSource::getDefaultSource($modx);
if (!$source->getWorkingContext()) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$source->setRequestProperties($scriptProperties);
$source->initialize();
$list = $source->getFilesInDirectory($dir);

return $this->outputArray($list);