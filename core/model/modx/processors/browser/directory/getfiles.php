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

/* get sanitized base path and current path */
$modx->getService('fileHandler','modFileHandler');
$root = $modx->fileHandler->getBasePath();
$dir = !isset($scriptProperties['dir']) || $scriptProperties['dir'] == 'root' ? '' : $scriptProperties['dir'];
$dir = $modx->fileHandler->sanitizePath($dir);
$fullpath = $root.'/'.$dir;

$imagesExts = array('jpg','jpeg','png','gif');
/* iterate */
$files = array();
foreach (new DirectoryIterator($fullpath) as $file) {
    if (in_array($file,array('.','..','.svn','_notes'))) continue;
    if (!$file->isReadable()) continue;

    $fileName = $file->getFilename();
    $filePathName = $file->getPathname();

    if (!$file->isDir()) {
        $fileExtension = pathinfo($filePathName,PATHINFO_EXTENSION);

	$filesize = @filesize($filePathName);
        /* calculate url */
	if (!empty($scriptProperties['prependUrl'])) {
            $url = $scriptProperties['prependUrl'].$dir.'/'.$fileName;
        } else {
            $url = $modx->getOption('rb_base_url').$dir.'/'.$fileName;
        }

        /* get thumbnail */
        if (in_array($fileExtension,$imagesExts)) {
            $thumb = str_replace('//','/',$modx->getOption('base_url').$url);
            $thumbWidth = $modx->getOption('filemanager_thumb_width',null,80);
            $thumbHeight = $modx->getOption('filemanager_thumb_height',null,60);
            $size = @getimagesize($filePathName);
            if (is_array($size)) {
                $thumbWidth = $size[0];
                $thumbHeight = $size[1];
            }
        } else {
            $thumb = $modx->getOption('manager_url').'templates/default/images/restyle/nopreview.jpg';
            $thumbWidth = $modx->getOption('filemanager_thumb_width',null,80);
            $thumbHeight = $modx->getOption('filemanager_thumb_height',null,60);
        }
        $octalPerms = substr(sprintf('%o', $file->getPerms()), -4);
        $files[] = array(
            'id' => $filePathName,
            'name' => $fileName,
            'cls' => 'icon-'.$fileExtension,
            'image' => $thumb,
            'image_width' => $thumbWidth,
            'image_height' => $thumbHeight,
            'url' => str_replace('//','/',$modx->getOption('base_url').$url),
            'relativeUrl' => $url,
            'ext' => $fileExtension,
            'pathname' => $filePathName,
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