<?php
/**
 * Get a list of directories and files, sorting them first by folder/file and
 * then alphanumerically.
 *
 * @param string $id The path to grab a list from
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 * @param boolean $hideFiles (optional) If true, will not display files.
 * Defaults to false.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
$modx->lexicon->load('file');

/* setup default properties */
$dir = !isset($scriptProperties['id']) || $scriptProperties['id'] == 'root'
        ? ''
        : strpos($scriptProperties['id'], 'n_') === 0 ? substr($scriptProperties['id'], 2) : $scriptProperties['id'];

$source = $modx->getOption('source',$scriptProperties,1);

/** @var modMediaSource $source */
$modx->loadClass('sources.modMediaSource');
$source = modMediaSource::getDefaultSource($modx,$source);
if (!$source->getWorkingContext()) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$source->setRequestProperties($scriptProperties);
$source->initialize();
$list = $source->getFolderList($dir);

return $modx->toJSON($list);