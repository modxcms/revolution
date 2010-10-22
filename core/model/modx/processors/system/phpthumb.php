<?php
/**
 * Generate a thumbnail
 * 
 * @package modx
 * @subpackage processors.system
 */
if (!isset($modx)) die();
$ctx = !empty($_REQUEST['ctx']) ? $_REQUEST['ctx'] : 'mgr';
$oldContext = $modx->context->get('key');
$modx->switchContext($ctx);

/* get modFileHandler service */
$modx->getService('fileHandler','modFileHandler');

/* filter path */
$src = $modx->getOption('src',$scriptProperties,'');
if (empty($src)) return '';

/* determine absolute path to image from URL passed that is context-specific */
$basePath = $modx->fileHandler->getBasePath(false,$ctx);
$src = $basePath.$src;
/* strip out double slashes */
$src = str_replace(array('///','//'),'/',$src);

/* check for file existence */
if (empty($src) || !file_exists($src)) {
    if (file_exists('/'.$src)) {
        $src = '/'.$src;
    } else {
        return '';
    }
}

/* load phpThumb */
if (!$modx->loadClass('modPhpThumb',$modx->getOption('core_path').'model/phpthumb/',true,true)) {
    $modx->log(modX::LOG_LEVEL_ERROR,'Could not load modPhpThumb class.');
    return '';
}
$phpThumb = new modPhpThumb($modx,$scriptProperties);
/* do initial setup */
$phpThumb->initialize();

/* set source and generate thumbnail */
$phpThumb->set($src);

/* check to see if there's a cached file of this already */
if ($phpThumb->checkForCachedFile()) {
    $phpThumb->loadCache();
    return '';
}

/* generate thumbnail */
$phpThumb->generate();

/* cache the thumbnail and output */
$phpThumb->cache();
$phpThumb->output();

$modx->switchContext($oldContext);
return '';