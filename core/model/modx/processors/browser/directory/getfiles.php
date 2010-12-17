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
 * @package modx
 * @subpackage processors.browser.directory
 */
if (!$modx->hasPermission('file_list')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

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

/* get sanitized base path and current path */
$root = $modx->fileHandler->getBasePath();
$dir = !isset($scriptProperties['dir']) || $scriptProperties['dir'] == 'root' ? '' : $scriptProperties['dir'];
$dir = $modx->fileHandler->sanitizePath($dir);
$dir = $modx->fileHandler->postfixSlash($dir);
$fullpath = $root.'/'.$dir;

/* get base path/url */
$basePath = $modx->fileHandler->getBasePath(false);
$baseUrl = $modx->fileHandler->getBaseUrl(true);
$isRelativeBaseUrl = $modx->getOption('filemanager_path_relative',null,true);

/* setup settings */
$imagesExts = array('jpg','jpeg','png','gif');
$use_multibyte = $modx->fileHandler->context->getOption('use_multibyte', false);
$encoding = $modx->fileHandler->context->getOption('modx_charset', 'UTF-8');
/* iterate */
$files = array();
if (!is_dir($fullpath)) return $modx->error->failure($modx->lexicon('file_folder_err_ns').$fullpath);
foreach (new DirectoryIterator($fullpath) as $file) {
    if (in_array($file,array('.','..','.svn','.git','_notes'))) continue;
    if (!$file->isReadable()) continue;

    $fileName = $file->getFilename();
    $filePathName = $file->getPathname();

    if (!$file->isDir()) {
        $fileExtension = pathinfo($filePathName,PATHINFO_EXTENSION);
        $fileExtension = $use_multibyte ? mb_strtolower($fileExtension,$encoding) : strtolower($fileExtension);

        $filesize = @filesize($filePathName);
        $url = $dir.$fileName;

        /* get thumbnail */
        if (in_array($fileExtension,$imagesExts)) {
            $imageWidth = $modx->fileHandler->context->getOption('filemanager_image_width', 400);
            $imageHeight = $modx->fileHandler->context->getOption('filemanager_image_height', 300);
            $thumbHeight = $modx->fileHandler->context->getOption('filemanager_thumb_height', 60);
            $thumbWidth = $modx->fileHandler->context->getOption('filemanager_thumb_width', 80);

            $size = @getimagesize($filePathName);
            if (is_array($size)) {
                $imageWidth = $size[0] > 800 ? 800 : $size[0];
                $imageHeight = $size[1] > 600 ? 600 : $size[1];
            }

            /* ensure max h/w */
            if ($thumbWidth > $imageWidth) $thumbWidth = $imageWidth;
            if ($thumbHeight > $imageHeight) $thumbHeight = $imageHeight;

            /* generate thumb/image URLs */
            $thumb = $modx->fileHandler->context->getOption('connectors_url', MODX_CONNECTORS_URL).'system/phpthumb.php?src='.$url.'&w='.$thumbWidth.'&h='.$thumbHeight.'&HTTP_MODAUTH='.$modx->site_id.'&wctx='.$workingContext->get('key');
            $image = $modx->fileHandler->context->getOption('connectors_url', MODX_CONNECTORS_URL).'system/phpthumb.php?src='.$url.'&w='.$imageWidth.'&h='.$imageHeight.'&HTTP_MODAUTH='.$modx->site_id.'&wctx='.$workingContext->get('key');

        } else {
            $thumb = $image = $modx->fileHandler->context->getOption('manager_url', MODX_MANAGER_URL).'templates/default/images/restyle/nopreview.jpg';
            $thumbWidth = $imageWidth = $modx->fileHandler->context->getOption('filemanager_thumb_width', 80);
            $thumbHeight = $imageHeight = $modx->fileHandler->context->getOption('filemanager_thumb_height', 60);
        }
        $octalPerms = substr(sprintf('%o', $file->getPerms()), -4);

        if ($isRelativeBaseUrl) {
            $url = ltrim($url,'/');
            $baseUrl = ltrim($baseUrl,'/');
        }
        $relativeUrl = $baseUrl.$url;

        $files[] = array(
            'id' => $filePathName,
            'name' => $fileName,
            'cls' => 'icon-'.$fileExtension,
            'image' => $image,
            'image_width' => $imageWidth,
            'image_height' => $imageHeight,
            'thumb' => $thumb,
            'thumb_width' => $thumb,
            'thumb_height' => $thumb,
            'url' => $url,
            'relativeUrl' => $relativeUrl,
            'ext' => $fileExtension,
            'pathname' => str_replace('//','/',$filePathName),
            'lastmod' => $file->getMTime(),
            'disabled' => false,
            'perms' => $octalPerms,
            'leaf' => true,
            'size' => $filesize,
            'menu' => array(
                array('text' => $modx->lexicon('file_remove'),'handler' => 'this.removeFile'),
            ),
        );
    }
}
return $this->outputArray($files,$count);
