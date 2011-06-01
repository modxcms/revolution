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
$modAuth = $_SESSION["modx.{$modx->context->get('key')}.user.token"];

$modx->getService('fileHandler','modFileHandler', '', array('context' => $workingContext->get('key')));

/* get sanitized base path and current path */
$dir = !isset($scriptProperties['dir']) || $scriptProperties['dir'] == 'root' ? '' : $scriptProperties['dir'];
$dir = $modx->fileHandler->sanitizePath($dir);
$dir = $modx->fileHandler->postfixSlash($dir);

/* get base path/url */
$basePathRelative = false;
if (empty($scriptProperties['basePath'])) {
    $basePath = $modx->fileHandler->getBasePath();
    $basePathRelative = $workingContext->getOption('filemanager_path_relative',true);
    if ($basePathRelative) {
        $basePathFull = $workingContext->getOption('base_path').ltrim($basePath,'/');
    } else {
        $basePathFull = $basePath;
    }
} else {
    $basePath = $scriptProperties['basePath'];
    if (!empty($scriptProperties['basePathRelative'])) {
        $basePathFull = $workingContext->getOption('base_path').ltrim($basePath,'/');
        $basePathRelative = true;
    } else {
        $basePathFull = $basePath;
    }
}
$baseUrlRelative = false;
if (empty($scriptProperties['baseUrl'])) {
    $baseUrl = $modx->fileHandler->getBaseUrl();
    $baseUrlRelative = $workingContext->getOption('filemanager_url_relative',true);
    if ($baseUrlRelative) {
        $baseUrlFull = $workingContext->getOption('base_url').ltrim($baseUrl,'/');
    } else {
        $baseUrlFull = $baseUrl;
    }
} else {
    $baseUrl = $scriptProperties['baseUrl'];
    if (!empty($scriptProperties['baseUrlRelative'])) {
        $baseUrlFull = $workingContext->getOption('base_url').ltrim($baseUrl,'/');
        $baseUrlRelative = true;
    } else {
        $baseUrlFull = $baseUrl;
    }
}
$fullPath = $basePathFull.ltrim($dir,'/');
if (empty($fullPath)) {
    $fullPath = $modx->fileHandler->getBasePath();
}

/* get default settings */
$imagesExts = array('jpg','jpeg','png','gif');
$use_multibyte = $modx->fileHandler->context->getOption('use_multibyte', false);
$encoding = $modx->fileHandler->context->getOption('modx_charset', 'UTF-8');
$allowedFileTypes = $modx->getOption('allowedFileTypes',$scriptProperties,'');
$allowedFileTypes = !empty($allowedFileTypes) && is_string($allowedFileTypes) ? explode(',',$allowedFileTypes) : $allowedFileTypes;

/* iterate */
$files = array();
if (!is_dir($fullPath)) return $modx->error->failure($modx->lexicon('file_folder_err_ns').$fullPath);
foreach (new DirectoryIterator($fullPath) as $file) {
    if (in_array($file,array('.','..','.svn','.git','_notes'))) continue;
    if (!$file->isReadable()) continue;

    $fileName = $file->getFilename();
    $filePathName = $file->getPathname();

    if (!$file->isDir()) {
        $fileExtension = pathinfo($filePathName,PATHINFO_EXTENSION);
        $fileExtension = $use_multibyte ? mb_strtolower($fileExtension,$encoding) : strtolower($fileExtension);

        if (!empty($allowedFileTypes) && !in_array($fileExtension,$allowedFileTypes)) continue;

        $filesize = @filesize($filePathName);
        $url = $dir.$fileName;
        if (!empty($scriptProperties['baseUrl'])) {
            $fullUrl = $scriptProperties['baseUrl'].ltrim($url,'/');
        } else {
            $fullUrl = $url;
        }

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
            $thumbQuery = http_build_query(array(
                'src' => $url,
                'w' => $thumbWidth,
                'h' => $thumbHeight,
                'f' => 'jpg',
                'q' => 90,
                'HTTP_MODAUTH' => $modAuth,
                'wctx' => $workingContext->get('key'),
                'basePath' => $basePath,
                'basePathRelative' => $basePathRelative,
                'baseUrl' => $baseUrl,
                'baseUrlRelative' => $baseUrlRelative,
            ));
            $imageQuery = http_build_query(array(
                'src' => $url,
                'w' => $imageWidth,
                'h' => $imageHeight,
                'HTTP_MODAUTH' => $modAuth,
                'f' => 'jpg',
                'q' => 90,
                'wctx' => $workingContext->get('key'),
                'basePath' => $basePath,
                'basePathRelative' => $basePathRelative,
                'baseUrl' => $baseUrl,
                'baseUrlRelative' => $baseUrlRelative,
            ));
            $thumb = $modx->fileHandler->context->getOption('connectors_url', MODX_CONNECTORS_URL).'system/phpthumb.php?'.$thumbQuery;
            $image = $modx->fileHandler->context->getOption('connectors_url', MODX_CONNECTORS_URL).'system/phpthumb.php?'.$imageQuery;

        } else {
            $thumb = $image = $modx->fileHandler->context->getOption('manager_url', MODX_MANAGER_URL).'templates/default/images/restyle/nopreview.jpg';
            $thumbWidth = $imageWidth = $modx->fileHandler->context->getOption('filemanager_thumb_width', 80);
            $thumbHeight = $imageHeight = $modx->fileHandler->context->getOption('filemanager_thumb_height', 60);
        }
        $octalPerms = substr(sprintf('%o', $file->getPerms()), -4);

        $files[] = array(
            'id' => $filePathName,
            'name' => $fileName,
            'cls' => 'icon-'.$fileExtension,
            'image' => $image,
            'image_width' => $imageWidth,
            'image_height' => $imageHeight,
            'thumb' => $thumb,
            'thumb_width' => $thumbWidth,
            'thumb_height' => $thumbHeight,
            'url' => ltrim($dir.$fileName,'/'),
            'relativeUrl' => ltrim($dir.$fileName,'/'),
            'fullRelativeUrl' => rtrim($baseUrl).ltrim($dir.$fileName,'/'),
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
