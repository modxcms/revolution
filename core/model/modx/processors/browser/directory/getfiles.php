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
if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

$dir = !isset($_REQUEST['dir']) || $_REQUEST['dir'] == 'root' ? '' : $_REQUEST['dir'];
$dir = trim($dir,'/');

$root = isset($_REQUEST['prependPath']) && $_REQUEST['prependPath'] != 'null' && $_REQUEST['prependPath'] != null
    ? $_REQUEST['prependPath']
    : $modx->getOption('base_path').$modx->getOption('rb_base_dir');
$fullpath = $root.'/'.$dir;

$files = array();
foreach (new DirectoryIterator($fullpath) as $file) {
	if (in_array($file,array('.','..','.svn','_notes'))) continue;
    if (!$file->isReadable()) continue;

    $fileName = $file->getFilename();
    $filePathName = $file->getPathname();

	if (!$file->isDir()) {
        $fileExtension = pathinfo($filePathName,PATHINFO_EXTENSION);

		$size = @filesize($fullname);
		if (isset($_REQUEST['prependUrl']) && $_REQUEST['prependUrl'] != null) {
            $url = $_REQUEST['prependUrl'].$dir.'/'.$fileName;
        } else {
            $url = $modx->getOption('rb_base_url').$dir.'/'.$fileName;
        }
        $octalPerms = substr(sprintf('%o', $file->getPerms()), -4);
		$files[] = array(
			'name' => $fileName,
			'cls' => 'icon-'.$fileExtension,
			'url' => $modx->getOption('base_url').$url,
			'ext' => $fileExtension,
			'pathname' => $filePathname,
			'lastmod' => $file->getMTime(),
			'disabled' => false,//$file->isWritable(),
            'perms' => $octalPerms,
			'leaf' => true,
			'size' => $size,
            'menu' => array(
                array('text' => $modx->lexicon('file_remove'),'handler' => 'this.removeFile'),
            ),
		);
	}
}
return $this->outputArray($files,$count);