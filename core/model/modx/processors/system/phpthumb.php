<?php
/**
 * Generate a thumbnail
 * 
 * @package modx
 * @subpackage processors.system
 */

/* filter path */
$src = $modx->getOption('src',$scriptProperties,'');
if (empty($src)) return '';
$src = str_replace(array(
    $modx->getOption('site_url',null,MODX_SITE_URL),
    $modx->getOption('base_url',null,MODX_BASE_URL),
),'',$src);
$src = $modx->getOption('base_path',null,MODX_BASE_PATH).$src;
if (empty($src) || !file_exists($src)) return '';

/* load phpThumb */
require_once MODX_CORE_PATH.'model/phpthumb/phpThumb.class.php';
$phpThumb = new phpThumb();

/* set cache dir */
$cachePath = $modx->getOption('core_path',null,MODX_CORE_PATH).'cache/phpthumb/';
if (!is_dir($cachePath)) $modx->cacheManager->writeTree($cachePath);
$phpThumb->config_cache_directory = $cachePath;
$phpThumb->setCacheDirectory();

$phpThumb->config_cache_maxage = (float)$modx->getOption('phpthumb_cache_maxage',$scriptProperties,30) * 86400;
$phpThumb->config_cache_maxsize = (float)$modx->getOption('phpthumb_cache_maxsize',$scriptProperties,100) * 1024 * 1024;
$phpThumb->config_cache_maxfiles = (int)$modx->getOption('phpthumb_cache_maxfiles',$scriptProperties,10000);
$phpThumb->cache_source_enabled = (boolean)$modx->getOption('phpthumb_cache_source_enabled',$scriptProperties,false);
$phpThumb->cache_source_directory = $cachePath.'source/';
$phpThumb->allow_local_http_src = true;

/* iterate through properties */
foreach ($scriptProperties as $property => $value) {
    $phpThumb->setParameter($property,$value);
}

/* set source and generate thumbnail */
$phpThumb->setSourceFilename($src);
if (!$phpThumb->GenerateThumbnail()) return '';

$outputFilename = $modx->getOption('output_filename',$scriptProperties,false);
$captureRawData = $modx->getOption('capture_raw_data',$scriptProperties,false);
if ($outputFilename) {
    $outputFilename = ltrim($outputFilename,'/');
    $outputFilename = ltrim($outputFilename,'\\');
    if (empty($outputFilename)) return '';
    
    $outputFilename = str_replace(array(
        '{base_path}',
        '{assets_path}',
        '{core_path}',
    ),array(
        $modx->getOption('base_path',null,MODX_BASE_PATH),
        $modx->getOption('assets_path',null,MODX_ASSETS_PATH),
        $modx->getOption('core_path',null,MODX_CORE_PATH),
    ),$outputFilename);

    if ($phpThumb->RenderToFile($outputFilename)) {
        return $modx->error->success('',array('filename' => $outputFilename));
    }
    return '';
} else {
    $phpThumb->OutputThumbnail();
}

return '';