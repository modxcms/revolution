<?php
/**
 * Generate a thumbnail
 * 
 * @package modx
 * @subpackage processors.system
 */
$ctx = !empty($_REQUEST['ctx']) ? $_REQUEST['ctx'] : 'mgr';
$context = $modx->getObject('modContext',$ctx);
$context->prepare();

/* filter path */
$src = $modx->getOption('src',$scriptProperties,'');
if (empty($src)) return '';

$site_url = $context->getOption('site_url',null,MODX_SITE_URL);
$base_url = $context->getOption('base_url',null,MODX_BASE_URL);
$base_path = $context->getOption('base_path',null,MODX_BASE_PATH);
$reps = array();

if ($base_url != '/') $reps[] = $base_url;
if ($site_url != '/') $reps[] = $site_url;
$src = str_replace($reps,'',$src);
$fileManagerPath = $context->getOption('filemanager_path',null,'');
if (empty($fileManagerPath)) {
    $src = $base_path.$src;
} else if (!empty($fileManagerPath)) {
    if (strpos($src,$fileManagerPath) != 0) {
        $src = $fileManagerPath.$src;
    }
}
if (empty($src) || !file_exists($src)) {
    if (file_exists('/'.$src)) {
        $src = '/'.$src;
    } elseif (file_exists($base_path.$src)) {
        $src = $base_path.$src;
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
return '';