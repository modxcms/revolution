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

/* setup default properties */
$hideFiles = !empty($scriptProperties['hideFiles']) && $scriptProperties['hideFiles'] != 'false' ? true : false;
$stringLiterals = !empty($scriptProperties['stringLiterals']) ? true : false;
$dir = !isset($scriptProperties['id']) || $scriptProperties['id'] == 'root' ? '' : str_replace('n_','',$scriptProperties['id']);

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

$directories = array();
$files = array();
$ls = array();

$actions = $modx->request->getAllActionIDs();

/* get permissions available */
$canChmodDirs = $modx->hasPermission('directory_chmod');
$canCreateDirs = $modx->hasPermission('directory_create');
$canListDirs = $modx->hasPermission('directory_list');
$canRemoveDirs = $modx->hasPermission('directory_remove');
$canUpdateDirs = $modx->hasPermission('directory_update');
$canListFiles = $modx->hasPermission('file_list');
$canRemoveFile = $modx->hasPermission('file_remove');
$canUpdateFile = $modx->hasPermission('file_update');
$canUpload = $modx->hasPermission('file_upload');

/* get base paths and sanitize incoming paths */
$dir = $modx->fileHandler->sanitizePath($dir);
$dir = $modx->fileHandler->postfixSlash($dir);
$root = $modx->fileHandler->getBasePath(false);
$fullPath = str_replace('//','/',$root.$dir);
if (!is_dir($fullPath)) return $modx->error->failure($modx->lexicon('file_folder_err_ns').$fullPath);

$editAction = false;
$act = $modx->getObject('modAction',array('controller' => 'system/file/edit'));
if ($act) { $editAction = $act->get('id'); }

/* get relative url */
$baseUrl = $modx->fileHandler->getBaseUrl(true);
$isRelativeBaseUrl = $modx->getOption('filemanager_path_relative',null,true);

/* get mb support settings */
$useMultibyte = $modx->getOption('use_multibyte',null,false);
$encoding = $modx->getOption('modx_charset',null,'UTF-8');

$imagesExts = array('jpg','jpeg','png','gif','ico');

/* iterate through directories */
foreach (new DirectoryIterator($fullPath) as $file) {
    if (in_array($file,array('.','..','.svn','.git','_notes'))) continue;
    if (!$file->isReadable()) continue;

    $fileName = $file->getFilename();
    $filePathName = $file->getPathname();
    $octalPerms = substr(sprintf('%o', $file->getPerms()), -4);

    /* handle dirs */
    if ($file->isDir() && $canListDirs) {
        $cls = 'folder';
        if ($canChmodDirs) $cls .= ' pchmod';
        if ($canCreateDirs) $cls .= ' pcreate';
        if ($canRemoveDirs) $cls .= ' premove';
        if ($canUpdateDirs) $cls .= ' pupdate';
        if ($canUpload) $cls .= ' pupload';

        $directories[$fileName] = array(
            'id' => $dir.$fileName,
            'text' => $fileName,
            'cls' => $cls,
            'type' => 'dir',
            'leaf' => false,
            'perms' => $octalPerms,
        );
    }

    /* get files in current dir */
    if ($file->isFile() && !$hideFiles && $canListFiles) {
        $ext = pathinfo($filePathName,PATHINFO_EXTENSION);
        $ext = $useMultibyte ? mb_strtolower($ext,$encoding) : strtolower($ext);

        $cls = 'icon-file icon-'.$ext;
        if ($canRemoveFile) $cls .= ' premove';
        if ($canUpdateFile) $cls .= ' pupdate';
        $encFile = rawurlencode($dir.$fileName);
        $page = !empty($editAction) ? '?a='.$editAction.'&file='.$encFile.'&wctx='.$workingContext->get('key') : null;

        /* get relative url from manager/ */
        $fromManagerUrl = $baseUrl.trim(str_replace('//','/',$dir.$fileName),'/');
        $fromManagerUrl = ($modx->getOption('filemanager_url_relative',null,true) ? '../' : '').$fromManagerUrl;

        /* get relative url for drag/drop */
        $url = $dir.$fileName;
        if ($baseUrl != '/') {
            $url = $baseUrl.str_replace('//','/',$dir.$fileName);
        }
        if ($isRelativeBaseUrl) {
            $url = ltrim($url,'/');
        }

        $files[$fileName] = array(
            'id' => $dir.$fileName,
            'text' => $fileName,
            'cls' => $cls,
            'type' => 'file',
            'leaf' => true,
            'qtip' => in_array($ext,$imagesExts) ? '<img src="'.$fromManagerUrl.'" alt="'.$fileName.'" />' : '',
            'page' => $modx->fileHandler->isBinary($filePathName) ? $page : null,
            'perms' => $octalPerms,
            'path' => $dir.$fileName,
            'url' => $url,
            'file' => $encFile,
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
