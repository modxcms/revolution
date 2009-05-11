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
$modx->lexicon->load('file');

if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));

$dir = !isset($_POST['dir']) || $_POST['dir'] == 'root' ? '' : $_POST['dir'];
$dir = trim($dir,'/');

$root = isset($_POST['prependPath']) && $_POST['prependPath'] != 'null' && $_POST['prependPath'] != null
    ? $_POST['prependPath']
    : $modx->getOption('base_path').$modx->getOption('rb_base_dir');
$fullpath = $root.'/'.$dir;
$odir = dir($fullpath);

$files = array();
while(false !== ($name = $odir->read())) {
	if('.' == $name || '..' == $name || '.svn' == $name) continue;

	$fullname = $fullpath.'/'.$name;
	if(!is_readable($fullname)) continue;

	if(!is_dir($fullname)) {
		$atmp = explode(".", $name);
		if (1 == sizeof($atmp)) { $fileExtension = ''; } else {
			$fileExtension = strtolower(array_pop($atmp));
		}
		$fileClass = $this->fileClass . $fileExtension;
		$size = @filesize($fullname);
		if (isset($_POST['prependUrl']) && $_POST['prependUrl'] != null) {
            $url = $_POST['prependUrl'].$dir.'/'.$name;
        } else {
            $url = $modx->getOption('rb_base_url').$dir.'/'.$name;
        }
		$files[] = array(
			'name' => $name,
			'cls' => 'file',
			'url' => $modx->getOption('base_url').$url,
			'ext' => $fileExtension,
			'pathname' => $fullname,
			'lastmod' => filemtime($fullname),
			'disabled' => is_writable($fullname),
			'leaf' => true,
			'size' => $size,
            'menu' => array(
                array('text' => $modx->lexicon('file_remove'),'handler' => 'this.removeFile'),
            ),
		);
	}
}
return $this->outputArray($files,$count);