<?php
/**
 * Get a list of directories and files, sorting them first by folder/file and
 * then alphanumerically.
 *
 * @param string $id The path to grab a list from
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 * @param boolean $hideFiles (optional) If true, will not display files.
 * Defaults to false.
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
$modx->lexicon->load('file');

if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_POST['hideFiles'] = isset($_POST['hideFiles']) &&
    ($_POST['hideFiles'] === true || $_POST['hideFiles'] === 'true') ? true : false;

$dir = !isset($_REQUEST['id']) || $_REQUEST['id'] == 'root' ? '' : str_replace('n_','',$_REQUEST['id']);

$directories = array();
$files = array();
$ls = array();

$actions = $modx->request->getAllActionIDs();

$root = isset($_POST['prependPath']) && $_POST['prependPath'] != 'null' && $_POST['prependPath'] != null
    ? $_POST['prependPath']
    : $modx->getOption('base_path').$modx->getOption('rb_base_dir');
$fullpath = $root.($dir != '' ? $dir : '');
$odir = dir($fullpath);
while(false !== ($name = $odir->read())) {
	if(in_array($name,array('.','..','.svn','_notes'))) continue;

	$fullname = $fullpath.'/'.$name;
	if(!is_readable($fullname)) continue;

	/* handle dirs */
	if(is_dir($fullname)) {
		$directories[$name] = array(
			'id' => $dir.'/'.$name,
			'text' => $name,
			'cls' => 'folder',
			'type' => 'dir',
			'disabled' => is_writable($fullname),
            'leaf' => false,
            'menu' => array(
                'items' => array(
                    array(
                        'text' => $modx->lexicon('file_folder_create_here'),
                        'handler' => 'function(itm,e) {
                            this.createDirectory(itm,e);
                        }',
                    ),
                    array(
                        'text' => $modx->lexicon('file_folder_chmod'),
                        'handler' => 'function(itm,e) {
                            this.chmodDirectory(itm,e);
                        }',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('file_folder_remove'),
                        'handler' => 'function(itm,e) {
                            this.remove("file_folder_confirm_remove");
                        }',
                    ),
                ),
            ),
		);
	}

    /* get files in current dir */
    if (!is_dir($fullname) && $_POST['hideFiles'] != true) {
        $ext = pathinfo($fullname,PATHINFO_EXTENSION);
        $files[$name] = array(
            'id' => $dir.'/'.$name,
            'text' => $name,
            'cls' => 'icon-file icon-'.$ext,
            'type' => 'file',
            'disabled' => is_writable($fullname),
            'leaf' => true,
            'menu' => array(
                'items' => array(
                    array(
                        'text' => $modx->lexicon('file_edit'),
                        'handler' => 'function() {
                            Ext.getCmp("modx_file_tree").loadAction("'
                                . 'a=' . $actions['system/file/edit']
                                . '&file=' . rawurlencode($fullname)
                             . '");
                        }',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('file_remove'),
                        'handler' => 'function(itm,e) {
                            this.removeFile(itm,e);
                        }',
                    ),
                ),
            ),
        );
    }
}

/* now sort files/directories */
ksort($directories);
foreach ($directories as $dir) {
    $ls[] = $dir;
}
ksort($files);
foreach ($files as $file) {
    $ls[] = $file;
}

return $this->toJSON($ls);