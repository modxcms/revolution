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

/* setup default properties */
$hideFiles = !empty($scriptProperties['hideFiles']) && $scriptProperties['hideFiles'] != 'false' ? true : false;
$stringLiterals = !empty($scriptProperties['stringLiterals']) ? true : false;
$dir = !isset($scriptProperties['id']) || $scriptProperties['id'] == 'root' ? '' : str_replace('n_','',$scriptProperties['id']);

$directories = array();
$files = array();
$ls = array();

$actions = $modx->request->getAllActionIDs();

/* get base paths and sanitize incoming paths */
$modx->getService('fileHandler','modFileHandler');
$dir = $modx->fileHandler->sanitizePath($dir);
$dir = $modx->fileHandler->postfixSlash($dir);
$root = $modx->fileHandler->getBasePath();
$fullpath = $root.$dir;

$relativeRootPath = $modx->fileHandler->postfixSlash($root);

/* iterate through directories */
foreach (new DirectoryIterator($fullpath) as $file) {
    if (in_array($file,array('.','..','.svn','_notes'))) continue;
    if (!$file->isReadable()) continue;

    $fileName = $file->getFilename();
    $filePathName = $file->getPathname();
    $octalPerms = substr(sprintf('%o', $file->getPerms()), -4);

    /* handle dirs */
    if ($file->isDir()) {
        $directories[$fileName] = array(
            'id' => $dir.$fileName,
            'text' => $fileName,
            'cls' => 'folder',
            'type' => 'dir',
            'leaf' => false,
            'perms' => $octalPerms,
        );
    }

    /* get files in current dir */
    if ($file->isFile() && !$hideFiles) {
        $ext = pathinfo($filePathName,PATHINFO_EXTENSION);
        $files[$fileName] = array(
            'id' => $dir.$fileName,
            'text' => $fileName,
            'cls' => 'icon-file icon-'.$ext,
            'type' => 'file',
            'leaf' => true,
            'perms' => $octalPerms,
            'path' => $relativeRootPath.$fileName,
            'file' => rawurlencode($filePathName),
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