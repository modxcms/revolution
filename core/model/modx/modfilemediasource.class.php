<?php
/**
 * @package modx
 * @subpackage mysql
 */
/**
 * @package modx
 * @subpackage mysql
 */
class modFileMediaSource extends modMediaSource {
    /** @var modFileHandler */
    public $fileHandler;

    public function initialize() {
        parent::initialize();
        $this->fileHandler = $this->xpdo->getService('fileHandler','modFileHandler', '', array('context' => $this->ctx->get('key')));
    }

    /**
     * Get base paths/urls and sanitize incoming paths
     * 
     * @param string $dir A path to the active directory
     * @return array
     */
    public function getBases($dir) {
        $bases = array();
        $dir = $this->fileHandler->sanitizePath($dir);
        $bases['pathIsRelative'] = false;
        if (empty($this->properties['basePath'])) {
            $bases['path'] = $this->fileHandler->getBasePath();
            if ($this->ctx->getOption('filemanager_path_relative',true)) {
                $bases['path'] = $this->ctx->getOption('base_path','').$bases['path'];
                $bases['pathIsRelative'] = true;
            }
        } else {
            $bases['path'] = $this->properties['basePath'];
            if (!empty($this->properties['basePathRelative'])) {
                $bases['path'] = $this->ctx->getOption('base_path').$bases['path'];
                $bases['pathIsRelative'] = true;
            }
        }
        
        if ($bases['path'] != '/' && strpos($dir,$bases['path']) === false) {
            $bases['pathFull'] = $bases['path'].ltrim($dir,'/');
        } else {
            $bases['pathFull'] = $dir;
        }

        if (is_dir($bases['pathFull'])) {
            $bases['pathFull'] = $this->fileHandler->postfixSlash($bases['pathFull']);
        }

        $bases['urlFull'] = $bases['pathFull'].$dir;
        if ($bases['url'] != '/') {
            $bases['urlFull'] = str_replace('//','/',$bases['url'].$dir);
        }
        if ($bases['urlIsRelative']) {
            $bases['urlFull'] = ltrim($bases['urlFull'],'/');
        }

        /* get relative url */
        $bases['urlIsRelative'] = false;
        if (empty($this->properties['baseUrl'])) {
            $bases['url'] = $this->fileHandler->getBaseUrl(true);
            $bases['urlIsRelative'] = $this->ctx->getOption('filemanager_path_relative',true);
        } else {
            $bases['url'] = $this->properties['baseUrl'];
            if (!empty($this->properties['baseUrlRelative'])) {
                $bases['url'] = $this->xpdo->getOption('base_url',$this->xpdo->getOption('base_url',null,MODX_BASE_URL)).$bases['url'];
                $bases['urlIsRelative'] = true;
            }
        }

        return $bases;
    }

    public function getEditActionId() {
        $editAction = false;
        /** @var modAction $act */
        $act = $this->xpdo->getObject('modAction',array('controller' => 'system/file/edit'));
        if ($act) { $editAction = $act->get('id'); }
        return $editAction;
    }
    
    public function getFolderList($dir) {
        $bases = $this->getBases($dir);
        if (empty($bases['path'])) return false;
        $fullPath = $bases['pathFull'];
        
        $useMultibyte = $this->getOption('use_multibyte',$this->properties,false);
        $encoding = $this->getOption('modx_charset',$this->properties,'UTF-8');
        $hideFiles = !empty($this->properties['hideFiles']) && $this->properties['hideFiles'] != 'false' ? true : false;
        $editAction = $this->getEditActionId();

        $imagesExts = $this->getOption('imageExtensions',$this->properties,'jpg,jpeg,png,gif,ico');
        $imagesExts = explode(',',$imagesExts);

        $skipFiles = $this->getOption('skipFiles',$this->properties,'.,..,.svn,.git,_notes,.DS_Store');
        $skipFiles = explode(',',$skipFiles);

        /* iterate through directories */
        /** @var DirectoryIterator $file */
        foreach (new DirectoryIterator($fullPath) as $file) {
            if (in_array($file,$skipFiles)) continue;
            if (!$file->isReadable()) continue;

            $fileName = $file->getFilename();
            $filePathName = $file->getPathname();
            $octalPerms = substr(sprintf('%o', $file->getPerms()), -4);

            /* handle dirs */
            $cls = array();
            if ($file->isDir() && $this->hasPermission('directory_list')) {
                $cls[] = 'folder';
                if ($this->hasPermission('directory_chmod')) $cls[] = 'pchmod';
                if ($this->hasPermission('directory_create')) $cls[] = 'pcreate';
                if ($this->hasPermission('directory_remove')) $cls[] = 'premove';
                if ($this->hasPermission('directory_update')) $cls[] = 'pupdate';
                if ($this->hasPermission('file_upload')) $cls[] = 'pupload';

                $directories[$fileName] = array(
                    'id' => $bases['urlFull'].$fileName,
                    'text' => $fileName,
                    'cls' => implode(' ',$cls),
                    'type' => 'dir',
                    'leaf' => false,
                    'path' => $bases['pathFull'].$fileName,
                    'perms' => $octalPerms,
                    'menu' => array(),
                );
                $directories[$fileName]['menu'] = array('items' => $this->getListContextMenu($file,$directories[$fileName]));
            }

            /* get files in current dir */
            if ($file->isFile() && !$hideFiles && $this->hasPermission('file_list')) {
                $ext = pathinfo($filePathName,PATHINFO_EXTENSION);
                $ext = $useMultibyte ? mb_strtolower($ext,$encoding) : strtolower($ext);

                $cls = array();
                $cls[] = 'icon-file';
                $cls[] = 'icon-'.$ext;

                if (!empty($this->properties['currentFile']) && rawurldecode($this->properties['currentFile']) == $fullPath.$fileName && $this->properties['currentAction'] == $editAction) {
                    $cls[] = 'active-node';
                }

                if ($this->hasPermission('file_remove')) $cls[] = 'premove';
                if ($this->hasPermission('file_update')) $cls[] = 'pupdate';

                if (!$file->isWritable()) {
                    $cls[] = 'icon-lock';
                }
                $encFile = rawurlencode($fullPath.$fileName);
                $page = !empty($editAction) ? '?a='.$editAction.'&file='.$encFile.'&wctx='.$this->ctx->get('key') : null;

                /* get relative url from manager/ */
                $fromManagerUrl = $bases['url'].trim(str_replace('//','/',$dir.$fileName),'/');
                $fromManagerUrl = ($bases['urlIsRelative'] ? '../' : '').$fromManagerUrl;
                
                $files[$fileName] = array(
                    'id' => $bases['urlFull'].$fileName,
                    'text' => $fileName,
                    'cls' => implode(' ',$cls),
                    'type' => 'file',
                    'leaf' => true,
                    'qtip' => in_array($ext,$imagesExts) ? '<img src="'.$fromManagerUrl.'" alt="'.$fileName.'" />' : '',
                    'page' => $this->fileHandler->isBinary($filePathName) ? $page : null,
                    'perms' => $octalPerms,
                    'path' => $bases['pathFull'].$fileName,
                    'directory' => $bases['pathFull'],
                    'url' => $bases['urlFull'],
                    'file' => $encFile,
                    'menu' => array(),
                );
                $files[$fileName]['menu'] = array('items' => $this->getListContextMenu($file,$files[$fileName]));
            }
        }

        $ls = array();
        /* now sort files/directories */
        ksort($directories);
        foreach ($directories as $dir) {
            $ls[] = $dir;
        }
        ksort($files);
        foreach ($files as $file) {
            $ls[] = $file;
        }


        return $ls;
    }

    public function getListContextMenu(DirectoryIterator $file,array $fileArray) {
        $menu = array();
        if (!$file->isDir()) { /* files */
            if ($this->hasPermission('file_update')) {
                if (!empty($fileArray['page'])) {
                    $menu[] = array(
                        'text' => $this->xpdo->lexicon('file_edit'),
                        'file' => $fileArray['file'],
                        'handler' => 'this.editFile',
                    );
                }
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('rename'),
                    'handler' => 'this.renameFile',
                );
            }
            if ($this->hasPermission('file_remove')) {
                if (!empty($menu)) $menu[] = '-';
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('file_remove'),
                    'handler' => 'this.removeFile',
                );
            }
        } else { /* directories */
            if ($this->hasPermission('directory_create')) {
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('file_folder_create_here'),
                    'handler' => 'this.createDirectory',
                );
            }
            if ($this->hasPermission('directory_chmod')) {
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('file_folder_chmod'),
                    'handler' => 'this.chmodDirectory',
                );
            }
            if ($this->hasPermission('directory_update')) {
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('rename'),
                    'handler' => 'this.renameDirectory',
                );
            }
            $menu[] = array(
                'text' => $this->xpdo->lexicon('directory_refresh'),
                'handler' => 'this.refreshActiveNode',
            );
            if ($this->hasPermission('file_upload')) {
                $menu[] = '-';
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('upload_files'),
                    'handler' => 'this.uploadFiles',
                );
            }
            if ($this->hasPermission('directory_remove')) {
                $menu[] = '-';
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('file_folder_remove'),
                    'handler' => 'this.removeDirectory',
                );
            }
        }
        return $menu;
    }

    /**
     * Create a filesystem folder
     *
     * @param string $name
     * @param string $parentFolder
     * @return boolean
     */
    public function createFolder($name,$parentFolder) {
        $bases = $this->getBases($parentFolder.'/'.$name);
        if ($parentFolder == '/') $parentFolder = $bases['path'];

        /* create modDirectory instance for containing directory and validate */
        /** @var modDirectory $parentDirectory */
        $parentDirectory = $this->fileHandler->make($parentFolder);
        if (!($parentDirectory instanceof modDirectory)) {
            $this->addError('parent',$this->xpdo->lexicon('file_folder_err_parent_invalid'));
            return false;
        }
        if (!$parentDirectory->isReadable() || !$parentDirectory->isWritable()) {
            $this->addError('parent',$this->xpdo->lexicon('file_folder_err_perms_parent'));
            return false;
        }

        /* create modDirectory instance for new path, validate doesnt already exist */
        $newDirectoryPath = $parentDirectory->getPath().$name;
        /** @var modDirectory $newDirectory */
        $newDirectory = $this->fileHandler->make($newDirectoryPath,array(),'modDirectory');
        if ($newDirectory->exists()) {
            $this->addError('name',$this->xpdo->lexicon('file_folder_err_ae'));
            return false;
        }

        /* actually create the directory */
        $result = $newDirectory->create();
        if ($result !== true) {
            $this->addError('name',$this->xpdo->lexicon('file_folder_err_create').$result);
            return false;
        }

        $this->xpdo->logManagerAction('directory_create','',$newDirectory->getPath());
        return true;
    }

    /**
     * @param string $folderPath
     * @return boolean
     */
    public function removeFolder($folderPath) {
        /* instantiate modDirectory object */
        /** @var modDirectory $directory */
        $directory = $this->fileHandler->make($folderPath);
        
        /* validate and check permissions on directory */
        if (!($directory instanceof modDirectory)) {
            $this->addError('path',$this->xpdo->lexicon('file_folder_err_invalid'));
            return false;
        }
        if (!$directory->isReadable() || !$directory->isWritable()) {
            $this->addError('path',$this->xpdo->lexicon('file_folder_err_perms_remove'));
            return false;
        }

        /* remove the directory */
        $result = $directory->remove();
        if ($result == false) {
            $this->addError('path',$this->xpdo->lexicon('file_folder_err_remove'));
        }

        $this->xpdo->logManagerAction('directory_remove','',$directory->getPath());
        return true;
    }

    /**
     * @param string $oldPath
     * @param string $newName
     * @return bool
     */
    public function renameFolder($oldPath,$newName) {
        $bases = $this->getBases($oldPath);

        /** @var modDirectory $oldDirectory */
        $oldDirectory = $this->fileHandler->make($oldPath);

        /* make sure is a directory and writable */
        if (!($oldDirectory instanceof modDirectory)) {
            $this->addError('name',$this->xpdo->lexicon('file_folder_err_invalid'));
            return false;
        }
        if (!$oldDirectory->isReadable() || !$oldDirectory->isWritable()) {
            $this->addError('name',$this->xpdo->lexicon('file_folder_err_perms'));
            return false;
        }

        /* sanitize new path */
        $newPath = $this->fileHandler->sanitizePath($newName);
        $newPath = $this->fileHandler->postfixSlash($newPath);
        $newPath = dirname($oldPath).DIRECTORY_SEPARATOR.$newPath;

        /* rename the dir */
        if (!$oldDirectory->rename($newPath)) {
            $this->addError('name',$this->xpdo->lexicon('file_folder_err_rename'));
            return false;
        }

        $this->xpdo->logManagerAction('directory_rename','',$oldDirectory->getPath());
        return true;
    }


    /**
     * @param string $oldPath
     * @param string $newName
     * @return bool
     */
    public function renameFile($oldPath,$newName) {
        $bases = $this->getBases($oldPath);

        /** @var modFile $oldFile */
        $oldFile = $this->fileHandler->make($oldPath);

        /* make sure is a directory and writable */
        if (!($oldFile instanceof modFile)) {
            $this->addError('name',$this->xpdo->lexicon('file_err_invalid'));
            return false;
        }
        if (!$oldFile->isReadable() || !$oldFile->isWritable()) {
            $this->addError('name',$this->xpdo->lexicon('file_folder_err_perms'));
            return false;
        }

        /* sanitize new path */
        $newPath = $this->fileHandler->sanitizePath($newName);
        $newPath = dirname($oldPath).DIRECTORY_SEPARATOR.$newPath;

        /* rename the file */
        if (!$oldFile->rename($newPath)) {
            $this->addError('name',$this->xpdo->lexicon('file_folder_err_rename'));
            return false;
        }

        $this->xpdo->logManagerAction('file_rename','',$oldFile->getPath());
        return true;
    }


    /**
     * @param string $filePath
     * @return array
     */
    public function getFile($filePath) {
        /** @var modFile $file */
        $file = $this->fileHandler->make($filePath);

        if (!$file->exists()) {
            $this->addError('file',$this->xpdo->lexicon('file_err_nf'));
        }
        if (!$file->isReadable()) {
            $this->addError('file',$this->xpdo->lexicon('file_err_perms'));
        }
        $imagesExts = array('jpg','jpeg','png','gif','ico');
        $fileExtension = pathinfo($filePath,PATHINFO_EXTENSION);

        $fa = array(
            'name' => $file->getPath(),
            'basename' => basename($file->getPath()),
            'size' => $file->getSize(),
            'last_accessed' => $file->getLastAccessed(),
            'last_modified' => $file->getLastModified(),
            'content' => $file->getContents(),
            'image' => in_array($fileExtension,$imagesExts) ? true : false,
            'is_writable' => $file->isWritable(),
            'is_readable' => $file->isReadable(),
        );
        return $fa;
    }

    public function removeFile($filePath) {
        $bases = $this->getBases($filePath);

        $fullPath = $filePath;
        if (!file_exists($fullPath)) {
            $this->addError('file',$this->xpdo->lexicon('file_folder_err_ns').': '.$fullPath);
            return false;
        }

        /** @var modFile $file */
        $file = $this->fileHandler->make($fullPath);

        /* verify file exists and is writable */
        if (!$file->exists()) {
            $this->addError('file',$this->xpdo->lexicon('file_err_nf').': '.$file->getPath());
            return false;
        } else if (!$file->isReadable() || !$file->isWritable()) {
            $this->addError('file',$this->xpdo->lexicon('file_err_perms_remove'));
            return false;
        } else if (!($file instanceof modFile)) {
            $this->addError('file',$this->xpdo->lexicon('file_err_invalid'));
            return false;
        }

        /* remove file */
        if (!$file->remove()) {
            $this->addError('file',$this->xpdo->lexicon('file_err_remove'));
            return false;
        }

        /* log manager action */
        $this->xpdo->logManagerAction('file_remove','',$file->getPath());
        return true;
    }

    public function updateFile($filePath,$content) {
        $bases = $this->getBases($filePath);

        $fullPath = $bases['path'].ltrim($filePath,'/');

        /** @var modFile $file */
        $file = $this->fileHandler->make($fullPath);

        /* verify file exists */
        if (!$file->exists()) {
            $this->addError('file',$this->xpdo->lexicon('file_err_nf').': '.$filePath);
            return false;
        }

        /* write file */
        $file->setContent($content);
        $file->save();

        $this->xpdo->logManagerAction('file_update','',$file->getPath());

        return rawurlencode($file->getPath());
    }
    
    public function uploadToFolder($targetDirectory,$files) {
        $bases = $this->getBases($targetDirectory);

        $fullPath = $bases['path'].ltrim($targetDirectory,'/');

        /** @var modDirectory $directory */
        $directory = $this->fileHandler->make($fullPath);

        /* verify target path is a directory and writable */
        if (!($directory instanceof modDirectory)) {
            $this->addError('path',$this->xpdo->lexicon('file_folder_err_invalid').': '.$fullPath);
            return false;
        }
        if (!($directory->isReadable()) || !$directory->isWritable()) {
            $this->addError('path',$this->xpdo->lexicon('file_folder_err_perms_upload').': '.$fullPath);
            return false;
        }

        $this->xpdo->context->prepare();
        $allowedFileTypes = explode(',',$this->xpdo->getOption('upload_files',null,''));
        $allowedFileTypes = array_merge(explode(',',$this->xpdo->getOption('upload_images')),explode(',',$this->xpdo->getOption('upload_media')),explode(',',$this->xpdo->getOption('upload_flash')),$allowedFileTypes);
        $allowedFileTypes = array_unique($allowedFileTypes);
        $maxFileSize = $this->xpdo->getOption('upload_maxsize',null,1048576);

        /* loop through each file and upload */
        foreach ($files as $file) {
            if ($file['error'] != 0) continue;
            if (empty($file['name'])) continue;
            $ext = @pathinfo($file['name'],PATHINFO_EXTENSION);
            $ext = strtolower($ext);

            if (empty($ext) || !in_array($ext,$allowedFileTypes)) {
                $this->addError('path',$this->xpdo->lexicon('file_err_ext_not_allowed',array(
                    'ext' => $ext,
                )));
                continue;
            }
            $size = @filesize($file['tmp_name']);

            if ($size > $maxFileSize) {
                $this->addError('path',$this->xpdo->lexicon('file_err_too_large',array(
                    'size' => $size,
                    'allowed' => $maxFileSize,
                )));
                continue;
            }

            $newPath = $this->fileHandler->sanitizePath($file['name']);
            $newPath = $directory->getPath().$newPath;

            if (!@move_uploaded_file($file['tmp_name'],$newPath)) {
                $this->addError('path',$this->xpdo->lexicon('file_err_upload'));
                continue;
            }
        }

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerUpload',array(
            'files' => &$_FILES,
            'directory' => &$directory,
        ));

        $this->xpdo->logManagerAction('file_upload','',$directory->getPath());

        return true;
    }

    public function chmodFolder($directoryPath,$mode) {
        /** @var modDirectory $directory */
        $directory = $this->fileHandler->make($directoryPath);

        /* verify target path is a directory and writable */
        if (!($directory instanceof modDirectory)) {
            $this->addError('mode',$this->xpdo->lexicon('file_folder_err_invalid').': '.$directoryPath);
            return false;
        }
        if (!$directory->isReadable() || !$directory->isWritable()) {
            $this->addError('mode',$this->xpdo->lexicon('file_folder_err_perms_upload').': '.$directoryPath);
            return false;
        }

        if (!$directory->chmod($mode)) {
            $this->addError('mode',$this->xpdo->lexicon('file_err_chmod'));
            return false;
        }

        $this->xpdo->logManagerAction('directory_chmod','',$directoryPath);
        return true;
    }
}