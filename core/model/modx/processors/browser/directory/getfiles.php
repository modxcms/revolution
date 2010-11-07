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
$ctx = !empty($scriptProperties['ctx']) ? $scriptProperties['ctx'] : 'mgr';

/* get sanitized base path and current path */
$modx->getService('fileHandler','modFileHandler');
$root = $modx->fileHandler->getBasePath();
$dir = !isset($scriptProperties['dir']) || $scriptProperties['dir'] == 'root' ? '' : $scriptProperties['dir'];
$dir = $modx->fileHandler->sanitizePath($dir);
$dir = $modx->fileHandler->postfixSlash($dir);
$fullpath = $root.'/'.$dir;

/* get base path/url */
$basePath = $modx->fileHandler->getBasePath(false);
$baseUrl = $modx->fileHandler->getBaseUrl(true);

/* setup settings */
$imagesExts = array('jpg','jpeg','png','gif');
$use_multibyte = $modx->getOption('use_multibyte',null,false);
$encoding = $modx->getOption('modx_charset',null,'UTF-8');
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
            $imageWidth = $modx->getOption('filemanager_image_width',null,400);
            $imageHeight = $modx->getOption('filemanager_image_height',null,300);
            $thumbHeight = $modx->getOption('filemanager_thumb_height',null,60);
            $thumbWidth = $modx->getOption('filemanager_thumb_width',null,80);

            $size = @getimagesize($filePathName);
            if (is_array($size)) {
                $imageWidth = $size[0] > 800 ? 800 : $size[0];
                $imageHeight = $size[1] > 600 ? 600 : $size[1];
            }

            /* ensure max h/w */
            if ($thumbWidth > $imageWidth) $thumbWidth = $imageWidth;
            if ($thumbHeight > $imageHeight) $thumbHeight = $imageHeight;

            /* generate thumb/image URLs */
            $thumb = $modx->getOption('connectors_url',null,MODX_CONNECTORS_URL).'system/phpthumb.php?src='.$url.'&w='.$thumbWidth.'&h='.$thumbHeight.'&HTTP_MODAUTH='.$modx->site_id.'&ctx='.$ctx;
            $image = $modx->getOption('connectors_url',null,MODX_CONNECTORS_URL).'system/phpthumb.php?src='.$url.'&w='.$imageWidth.'&h='.$imageHeight.'&HTTP_MODAUTH='.$modx->site_id.'&ctx='.$ctx;

        } else {
            $thumb = $image = $modx->getOption('manager_url',null,MODX_MANAGER_URL).'templates/default/images/restyle/nopreview.jpg';
            $thumbWidth = $imageWidth = $modx->getOption('filemanager_thumb_width',null,80);
            $thumbHeight = $imageHeight = $modx->getOption('filemanager_thumb_height',null,60);
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
            'thumb_width' => $thumb,
            'thumb_height' => $thumb,
            'url' => $url,
            'relativeUrl' => str_replace('//','/',$baseUrl.$url),
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
