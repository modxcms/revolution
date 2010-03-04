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
if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('file');

$hideFiles = !empty($scriptProperties['hideFiles']) && $scriptProperties['hideFiles'] != 'false' ? true : false;
$stringLiterals = !empty($scriptProperties['stringLiterals']) ? true : false;

$dir = !isset($scriptProperties['id']) || $scriptProperties['id'] == 'root' ? '' : str_replace('n_','',$scriptProperties['id']);

$directories = array();
$files = array();
$ls = array();

$actions = $modx->request->getAllActionIDs();

$root = !empty($scriptProperties['prependPath']) && $scriptProperties['prependPath'] != 'null'
    ? $scriptProperties['prependPath']
    : $modx->getOption('base_path').$modx->getOption('rb_base_dir');

$fullpath = $root.($dir != '' ? $dir : '');
foreach (new DirectoryIterator($fullpath) as $file) {
	if (in_array($file,array('.','..','.svn','_notes'))) continue;
	if (!$file->isReadable()) continue;

    $fileName = $file->getFilename();
    $filePathName = $file->getPathname();
    $octalPerms = substr(sprintf('%o', $file->getPerms()), -4);

	/* handle dirs */
	if ($file->isDir()) {
		$directories[$fileName] = array(
			'id' => $dir.'/'.$fileName,
			'text' => $fileName,
			'cls' => 'folder',
			'type' => 'dir',
			//'disabled' => $file->isWritable(),
            'leaf' => false,
            'perms' => $octalPerms,
            'menu' => array(
                'items' => array(
                    array(
                        'text' => $modx->lexicon('file_folder_create_here'),
                        'handler' => 'function(itm,e) {
                            this.createDirectory(itm,e);
                        }',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('file_folder_chmod'),
                        'handler' => 'function(itm,e) {
                            this.chmodDirectory(itm,e);
                        }',
                    ),
                    array(
                        'text' => $modx->lexicon('rename'),
                        'handler' => 'function(itm,e) {
                            this.renameFile(itm,e);
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
    if ($file->isFile() && !$hideFiles) {
        $ext = pathinfo($filePathName,PATHINFO_EXTENSION);
        $files[$fileName] = array(
            'id' => $dir.'/'.$fileName,
            'text' => $fileName,
            'cls' => 'icon-file icon-'.$ext,
            'type' => 'file',
            'leaf' => true,
            'perms' => $octalPerms,
            'menu' => array(
                'items' => array(
                    array(
                        'text' => $modx->lexicon('file_edit'),
                        'handler' => 'function() {
                            Ext.getCmp("modx-file-tree").loadAction("'
                                . 'a=' . $actions['system/file/edit']
                                . '&file=' . rawurlencode($filePathName)
                             . '");
                        }',
                    ),
                    array(
                        'text' => $modx->lexicon('rename'),
                        'handler' => 'function(itm,e) {
                            this.renameFile(itm,e);
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


if ($stringLiterals) {
    return $modx->toJSON($ls);
} else {
    return $this->toJSON($ls);
}