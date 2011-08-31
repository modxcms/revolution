<?php
/**
 * Generate a thumbnail
 *
 * @var modX $modx
 * @var array $scriptProperties
 * 
 * @package modx
 * @subpackage processors.system
 */
if (!isset($modx)) die();

/* get modFileHandler service */
$wctx = isset($scriptProperties['wctx']) && !empty($scriptProperties['wctx']) ? $scriptProperties['wctx'] : $modx->context->get('key');
$modx->getService('fileHandler','modFileHandler','',array('context' => $wctx));

/* filter path */
$src = $modx->getOption('src',$scriptProperties,'');
if (empty($src)) return '';

$source = $modx->getOption('source',$scriptProperties,1);

/** @var modMediaSource|modFileMediaSource $source */
$modx->loadClass('sources.modMediaSource');
$source = modMediaSource::getDefaultSource($modx,$source);
if (empty($source)) return '';

if (!$source->getWorkingContext()) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$source->setRequestProperties($scriptProperties);
$source->initialize();
$src = $source->prepareSrcForThumb($src);
if (empty($src)) return '';


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

return '';
