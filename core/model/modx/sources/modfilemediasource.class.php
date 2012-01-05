<?php
/**
 * @package modx
 * @subpackage sources
 */
require_once MODX_CORE_PATH . 'model/modx/sources/modmediasource.class.php';
/**
 * Implements a file-system-based media source, allowing manipulation and management of files on the server's
 * location. Supports basePath and baseUrl parameters, similar to Revolution 2.1 and prior's filemanager_* settings.
 *
 * @package modx
 * @subpackage sources
 */
class modFileMediaSource extends modMediaSource implements modMediaSourceInterface {
    /** @var modFileHandler */
    public $fileHandler;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        parent::initialize();
        $options = array();
        if (!$this->ctx) {
            $this->ctx =& $this->xpdo->context;
        }
        $options['context'] = $this->ctx->get('key');
        $this->fileHandler = $this->xpdo->getService('fileHandler','modFileHandler', '',$options);
        return true;
    }

    /**
     * Get base paths/urls and sanitize incoming paths
     * 
     * @param string $path A path to the active directory
     * @return array
     */
    public function getBases($path) {
        if (empty($path)) $path = '';
        $properties = $this->getProperties();
        $bases = array();
        $path = $this->fileHandler->sanitizePath($path);
        $bases['path'] = $properties['basePath']['value'];
        $bases['pathIsRelative'] = false;
        if (!empty($properties['basePathRelative']['value'])) {
            $bases['pathAbsolute'] = $this->ctx->getOption('base_path',MODX_BASE_PATH).$bases['path'];
            $bases['pathIsRelative'] = true;
        } else {
            $bases['pathAbsolute'] = $bases['path'];
        }
        
        $bases['pathAbsoluteWithPath'] = $bases['pathAbsolute'].ltrim($path,'/');
        if (is_dir($bases['pathAbsoluteWithPath'])) {
            $bases['pathAbsoluteWithPath'] = $this->fileHandler->postfixSlash($bases['pathAbsoluteWithPath']);
        }
        $bases['pathRelative'] = ltrim($path,'/');

        /* get relative url */
        $bases['urlIsRelative'] = false;
        $bases['url'] = $properties['baseUrl']['value'];;
        if (!empty($properties['baseUrlRelative']['value'])) {
            $bases['urlAbsolute'] = $this->ctx->getOption('base_url',MODX_BASE_URL).$bases['url'];
            $bases['urlIsRelative'] = true;
        } else {
            $bases['urlAbsolute'] = $bases['url'];
        }

        $bases['urlAbsoluteWithPath'] = $bases['urlAbsolute'].ltrim($path,'/');
        $bases['urlRelative'] = ltrim($path,'/');
        return $bases;
    }

    /**
     * Get the ID of the edit file action
     *
     * @return boolean|int
     */
    public function getEditActionId() {
        $editAction = false;
        /** @var modAction $act */
        $act = $this->xpdo->getObject('modAction',array('controller' => 'system/file/edit'));
        if ($act) { $editAction = $act->get('id'); }
        return $editAction;
    }

    /**
     * Return an array of files and folders at this current level in the directory structure
     * 
     * @param string $path
     * @return array
     */
    public function getContainerList($path) {
        $properties = $this->getPropertyList();
        $path = $this->fileHandler->postfixSlash($path);
        $bases = $this->getBases($path);
        if (empty($bases['pathAbsolute'])) return array();
        $fullPath = $bases['pathAbsolute'].ltrim($path,'/');
                
        $useMultibyte = $this->getOption('use_multibyte',$properties,false);
        $encoding = $this->getOption('modx_charset',$properties,'UTF-8');
        $hideFiles = !empty($properties['hideFiles']) && $properties['hideFiles'] != 'false' ? true : false;
        $editAction = $this->getEditActionId();

        $imagesExts = $this->getOption('imageExtensions',$properties,'jpg,jpeg,png,gif');
        $imagesExts = explode(',',$imagesExts);
        $skipFiles = $this->getOption('skipFiles',$properties,'.svn,.git,_notes,.DS_Store,nbproject,.idea');
        $skipFiles = explode(',',$skipFiles);
        if ($this->xpdo->getParser()) {
            $this->xpdo->parser->processElementTags('',$skipFiles,true,true);
        }
        $skipFiles[] = '.';
        $skipFiles[] = '..';

        $canSave = $this->checkPolicy('save');
        $canRemove = $this->checkPolicy('remove');
        $canCreate = $this->checkPolicy('create');

        $directories = array();
        $files = array();
        if (!is_dir($fullPath)) return array();

        /* iterate through directories */
        /** @var DirectoryIterator $file */
        foreach (new DirectoryIterator($fullPath) as $file) {
            if (in_array($file,$skipFiles)) continue;
            if (!$file->isReadable()) continue;

            $fileName = $file->getFilename();
            if (in_array(trim($fileName,'/'),$skipFiles)) continue;
            if (in_array($fullPath.$fileName,$skipFiles)) continue;
            $filePathName = $file->getPathname();
            $octalPerms = substr(sprintf('%o', $file->getPerms()), -4);

            /* handle dirs */
            $cls = array();
            if ($file->isDir() && $this->hasPermission('directory_list')) {
                $cls[] = 'folder';
                if ($this->hasPermission('directory_chmod') && $canSave) $cls[] = 'pchmod';
                if ($this->hasPermission('directory_create') && $canCreate) $cls[] = 'pcreate';
                if ($this->hasPermission('directory_remove') && $canRemove) $cls[] = 'premove';
                if ($this->hasPermission('directory_update') && $canSave) $cls[] = 'pupdate';
                if ($this->hasPermission('file_upload') && $canCreate) $cls[] = 'pupload';
                if ($this->hasPermission('file_create') && $canCreate) $cls[] = 'pcreate';

                $directories[$fileName] = array(
                    'id' => $bases['urlRelative'].rtrim($fileName,'/').'/',
                    'text' => $fileName,
                    'cls' => implode(' ',$cls),
                    'type' => 'dir',
                    'leaf' => false,
                    'path' => $bases['pathAbsoluteWithPath'].$fileName,
                    'pathRelative' => $bases['pathRelative'].$fileName,
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

                if (!empty($properties['currentFile']) && rawurldecode($properties['currentFile']) == $fullPath.$fileName && $properties['currentAction'] == $editAction) {
                    $cls[] = 'active-node';
                }

                if ($this->hasPermission('file_remove') && $canRemove) $cls[] = 'premove';
                if ($this->hasPermission('file_update') && $canSave) $cls[] = 'pupdate';

                if (!$file->isWritable()) {
                    $cls[] = 'icon-lock';
                }
                $encFile = rawurlencode($fullPath.$fileName);
                $page = !empty($editAction) ? '?a='.$editAction.'&file='.$bases['urlRelative'].$fileName.'&wctx='.$this->ctx->get('key').'&source='.$this->get('id') : null;
                $url = ($bases['urlIsRelative'] ? $bases['urlRelative'] : $bases['url']).$fileName;

                /* get relative url from manager/ */
                $fromManagerUrl = $bases['url'].trim(str_replace('//','/',$path.$fileName),'/');
                $fromManagerUrl = ($bases['urlIsRelative'] ? '../' : '').$fromManagerUrl;
                $files[$fileName] = array(
                    'id' => $bases['urlRelative'].$fileName,
                    'text' => $fileName,
                    'cls' => implode(' ',$cls),
                    'type' => 'file',
                    'leaf' => true,
                    'qtip' => in_array($ext,$imagesExts) ? '<img src="'.$fromManagerUrl.'" alt="'.$fileName.'" />' : '',
                    'page' => $this->fileHandler->isBinary($filePathName) ? $page : null,
                    'perms' => $octalPerms,
                    'path' => $bases['pathAbsoluteWithPath'].$fileName,
                    'pathRelative' => $bases['pathRelative'].$fileName,
                    'directory' => $bases['path'],
                    'url' => $bases['url'].$url,
                    'urlAbsolute' => $bases['urlAbsolute'].ltrim($url,'/'),
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

    /**
     * Get the context menu items for a specific object in the list view
     * 
     * @param DirectoryIterator $file
     * @param array $fileArray
     * @return array
     */
    public function getListContextMenu(DirectoryIterator $file,array $fileArray) {
        $canSave = $this->checkPolicy('save');
        $canRemove = $this->checkPolicy('remove');
        $canCreate = $this->checkPolicy('create');
        $canView = $this->checkPolicy('view');

        $menu = array();
        if (!$file->isDir()) { /* files */
            if ($this->hasPermission('file_update') && $canSave) {
                if (!empty($fileArray['page'])) {
                    $menu[] = array(
                        'text' => $this->xpdo->lexicon('file_edit'),
                        'handler' => 'this.editFile',
                    );
                }
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('rename'),
                    'handler' => 'this.renameFile',
                );
            }
            if ($this->hasPermission('file_view') && $canView) {
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('file_download'),
                    'handler' => 'this.downloadFile',
                );
            }
            if ($this->hasPermission('file_remove') && $canRemove) {
                if (!empty($menu)) $menu[] = '-';
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('file_remove'),
                    'handler' => 'this.removeFile',
                );
            }
        } else { /* directories */
            if ($this->hasPermission('directory_create') && $canCreate) {
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('file_folder_create_here'),
                    'handler' => 'this.createDirectory',
                );
            }
            if ($this->hasPermission('directory_chmod') && $canSave) {
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('file_folder_chmod'),
                    'handler' => 'this.chmodDirectory',
                );
            }
            if ($this->hasPermission('directory_update') && $canSave) {
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('rename'),
                    'handler' => 'this.renameDirectory',
                );
            }
            $menu[] = array(
                'text' => $this->xpdo->lexicon('directory_refresh'),
                'handler' => 'this.refreshActiveNode',
            );
            if ($this->hasPermission('file_upload') && $canCreate) {
                $menu[] = '-';
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('upload_files'),
                    'handler' => 'this.uploadFiles',
                );
            }
            if ($this->hasPermission('file_create') && $canCreate) {
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('file_create'),
                    'handler' => 'this.createFile',
                );
            }
            if ($this->hasPermission('directory_remove') && $canRemove) {
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
     * @param string $parentContainer
     * @return boolean
     */
    public function createContainer($name,$parentContainer) {
        $bases = $this->getBases($parentContainer.'/'.$name);
        if ($parentContainer == '/') {
            $parentContainer = $bases['pathAbsolute'];
        } else {
            $parentContainer = $bases['pathAbsolute'].$parentContainer;
        }

        /* create modDirectory instance for containing directory and validate */
        /** @var modDirectory $parentDirectory */
        $parentDirectory = $this->fileHandler->make($parentContainer);
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
     * Remove a folder at the specified location
     * 
     * @param string $path
     * @return boolean
     */
    public function removeContainer($path) {
        /* instantiate modDirectory object */
        /** @var modDirectory $directory */
        $path = $this->fileHandler->postfixSlash($path);
        $directory = $this->fileHandler->make($path);
        
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
    public function renameContainer($oldPath,$newName) {
        $bases = $this->getBases($oldPath);
        $oldPath = $bases['pathAbsolute'].$oldPath;

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
    public function renameObject($oldPath,$newName) {
        $bases = $this->getBases($oldPath);
        $oldPath = $bases['pathAbsolute'].$oldPath;

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
     * Get the contents of a specified file
     * 
     * @param string $objectPath
     * @return array
     */
    public function getObjectContents($objectPath) {
        $properties = $this->getPropertyList();
        $bases = $this->getBases($objectPath);
        /** @var modFile $file */
        $file = $this->fileHandler->make($bases['pathAbsoluteWithPath']);

        if (!$file->exists()) {
            $this->addError('file',$this->xpdo->lexicon('file_err_nf'));
        }
        if (!$file->isReadable()) {
            $this->addError('file',$this->xpdo->lexicon('file_err_perms'));
        }
        $imageExtensions = $this->getOption('imageExtensions',$properties,'jpg,jpeg,png,gif');
        $imageExtensions = explode(',',$imageExtensions);
        $fileExtension = pathinfo($objectPath,PATHINFO_EXTENSION);

        $fa = array(
            'name' => $objectPath,
            'basename' => basename($file->getPath()),
            'path' => $file->getPath(),
            'size' => @$file->getSize(),
            'last_accessed' => @$file->getLastAccessed(),
            'last_modified' => @$file->getLastModified(),
            'content' => $file->getContents(),
            'image' => in_array($fileExtension,$imageExtensions) ? true : false,
            'is_writable' => $file->isWritable(),
            'is_readable' => $file->isReadable(),
        );
        return $fa;
    }

    /**
     * Remove a file
     * 
     * @param string $objectPath
     * @return boolean
     */
    public function removeObject($objectPath) {
        $bases = $this->getBases($objectPath);

        $fullPath = $bases['pathAbsolute'].$objectPath;
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

    /**
     * Update the contents of a file
     *
     * @param string $objectPath
     * @param string $content
     * @return boolean|string
     */
    public function updateObject($objectPath,$content) {
        $bases = $this->getBases($objectPath);

        $fullPath = $bases['pathAbsolute'].ltrim($objectPath,'/');

        /** @var modFile $file */
        $file = $this->fileHandler->make($fullPath);

        /* verify file exists */
        if (!$file->exists()) {
            $this->addError('file',$this->xpdo->lexicon('file_err_nf').': '.$objectPath);
            return false;
        }

        /* write file */
        $file->setContent($content);
        $file->save();

        $this->xpdo->logManagerAction('file_update','',$file->getPath());

        return rawurlencode($file->getPath());
    }


    /**
     * Create a file
     *
     * @param string $objectPath
     * @param string $name
     * @param string $content
     * @return boolean|string
     */
    public function createObject($objectPath,$name,$content) {
        $bases = $this->getBases($objectPath);

        $fullPath = $bases['pathAbsolute'].ltrim($objectPath,'/').ltrim($name,'/');

        /** @var modFile $file */
        $file = $this->fileHandler->make($fullPath,array(),'modFile');

        /* write file */
        $file->setContent($content);
        $file->create($content);
        
        /* verify file exists */
        if (!$file->exists()) {
            $this->addError('file',$this->xpdo->lexicon('file_err_nf').': '.$fullPath);
            return false;
        }

        $this->xpdo->logManagerAction('file_create','',$file->getPath());

        return rawurlencode($file->getPath());
    }

    /**
     * Upload files to a specific folder on the file system
     * 
     * @param string $container
     * @param array $objects
     * @return boolean
     */
    public function uploadObjectsToContainer($container,array $objects = array()) {
        $bases = $this->getBases($container);

        $fullPath = $bases['pathAbsolute'].ltrim($container,'/');

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
        foreach ($objects as $file) {
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
            'files' => &$objects,
            'directory' => $container,
            'source' => &$this,
        ));

        $this->xpdo->logManagerAction('file_upload','',$directory->getPath());

        return true;
    }

    /**
     * Chmod a specific folder
     * 
     * @param string $directoryPath
     * @param string $mode
     * @return boolean
     */
    public function chmodContainer($directoryPath,$mode) {
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

    /**
     * Move a file or folder to a specific location
     *
     * @param string $from The location to move from
     * @param string $to The location to move to
     * @param string $point
     * @return boolean
     */
    public function moveObject($from,$to,$point = 'append') {
        $success = false;
        $fromBases = $this->getBases($from);
        $toBases = $this->getBases($to);

        $fromPath = $fromBases['pathAbsolute'].$from;
        $toPath = $toBases['pathAbsolute'].$to;

        /* verify source path */
        if (!file_exists($fromPath)) {
            $this->addError('from',$this->xpdo->lexicon('file_err_nf').': '.$fromPath);
        }
        /** @var modFileSystemResource $fromObject */
        $fromObject = $this->fileHandler->make($fromPath);
        if (!$fromObject->isReadable() || !$fromObject->isWritable()) {
            $this->addError('from',$this->xpdo->lexicon('file_err_nf').': '.$fromPath);
            return $success;
        }

        /* verify target path */
        if (!file_exists($toPath)) {
            $this->addError('to',$this->xpdo->lexicon('file_folder_err_invalid').': '.$toPath);
        }
        /** @var modDirectory $toObject */
        $toObject = $this->fileHandler->make($toPath);
        if (!($toObject instanceof modDirectory)) {
            $this->addError('mode',$this->xpdo->lexicon('file_folder_err_invalid').': '.$toPath);
            return $success;
        }
        if (!$toObject->isReadable() || !$toObject->isWritable()) {
            $this->addError('to',$this->xpdo->lexicon('file_folder_err_invalid').': '.$toPath);
            return $success;
        }

        /* now move object */
        $newPath = rtrim($toPath,'/').'/'.basename($fromPath);
        $success = $fromObject->rename($newPath);
        if (!$success) {
            $this->addError('from',$this->xpdo->lexicon('file_err_chmod'));
        }
        return $success;
    }

    /**
     * Get a list of files in a specific directory.
     *
     * @param string $path
     * @return array
     */
    public function getObjectsInContainer($path) {
        $properties = $this->getPropertyList();
        $dir = $this->fileHandler->postfixSlash($path);
        $bases = $this->getBases($dir);
        if (empty($bases['pathAbsolute'])) return array();
        $fullPath = $bases['pathAbsolute'].$dir;

        $modAuth = $this->xpdo->user->getUserToken($this->xpdo->context->get('key'));

        /* get default settings */
        $imageExtensions = $this->getOption('imageExtensions',$properties,'jpg,jpeg,png,gif');
        $imageExtensions = explode(',',$imageExtensions);
        $use_multibyte = $this->ctx->getOption('use_multibyte', false);
        $encoding = $this->ctx->getOption('modx_charset', 'UTF-8');
        $allowedFileTypes = $this->getOption('allowedFileTypes',$properties,'');
        $allowedFileTypes = !empty($allowedFileTypes) && is_string($allowedFileTypes) ? explode(',',$allowedFileTypes) : $allowedFileTypes;
        $thumbnailType = $this->getOption('thumbnailType',$properties,'png');
        $thumbnailQuality = $this->getOption('thumbnailQuality',$properties,90);
        $skipFiles = $this->getOption('skipFiles',$properties,'.svn,.git,_notes,.DS_Store');
        $skipFiles = explode(',',$skipFiles);
        $skipFiles[] = '.';
        $skipFiles[] = '..';

        /* iterate */
        $files = array();
        if (!is_dir($fullPath)) {
            $this->addError('dir',$this->xpdo->lexicon('file_folder_err_ns').$fullPath);
            return array();
        }
        /** @var DirectoryIterator $file */
        foreach (new DirectoryIterator($fullPath) as $file) {
            if (in_array($file,$skipFiles)) continue;
            if (!$file->isReadable()) continue;

            $fileName = $file->getFilename();
            $filePathName = $file->getPathname();

            if (!$file->isDir()) {

                $fileExtension = pathinfo($filePathName,PATHINFO_EXTENSION);
                $fileExtension = $use_multibyte ? mb_strtolower($fileExtension,$encoding) : strtolower($fileExtension);

                if (!empty($allowedFileTypes) && !in_array($fileExtension,$allowedFileTypes)) continue;

                $filesize = @filesize($filePathName);
                $url = ltrim($dir.$fileName,'/');

                /* get thumbnail */
                if (in_array($fileExtension,$imageExtensions)) {
                    $imageWidth = $this->ctx->getOption('filemanager_image_width', 400);
                    $imageHeight = $this->ctx->getOption('filemanager_image_height', 300);
                    $thumbHeight = $this->ctx->getOption('filemanager_thumb_height', 60);
                    $thumbWidth = $this->ctx->getOption('filemanager_thumb_width', 80);

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
                        'f' => $thumbnailType,
                        'q' => $thumbnailQuality,
                        'HTTP_MODAUTH' => $modAuth,
                        'wctx' => $this->ctx->get('key'),
                        'source' => $this->get('id'),
                    ));
                    $imageQuery = http_build_query(array(
                        'src' => $url,
                        'w' => $imageWidth,
                        'h' => $imageHeight,
                        'HTTP_MODAUTH' => $modAuth,
                        'f' => $thumbnailType,
                        'q' => $thumbnailQuality,
                        'wctx' => $this->ctx->get('key'),
                        'source' => $this->get('id'),
                    ));
                    $thumb = $this->ctx->getOption('connectors_url', MODX_CONNECTORS_URL).'system/phpthumb.php?'.urldecode($thumbQuery);
                    $image = $this->ctx->getOption('connectors_url', MODX_CONNECTORS_URL).'system/phpthumb.php?'.urldecode($imageQuery);
                } else {
                    $thumb = $image = $this->ctx->getOption('manager_url', MODX_MANAGER_URL).'templates/default/images/restyle/nopreview.jpg';
                    $thumbWidth = $imageWidth = $this->ctx->getOption('filemanager_thumb_width', 80);
                    $thumbHeight = $imageHeight = $this->ctx->getOption('filemanager_thumb_height', 60);
                }
                $octalPerms = substr(sprintf('%o', $file->getPerms()), -4);

                $files[] = array(
                    'id' => $bases['urlAbsoluteWithPath'].$fileName,
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
                    'fullRelativeUrl' => rtrim($bases['url']).ltrim($dir.$fileName,'/'),
                    'ext' => $fileExtension,
                    'pathname' => str_replace('//','/',$filePathName),
                    'lastmod' => $file->getMTime(),
                    'disabled' => false,
                    'perms' => $octalPerms,
                    'leaf' => true,
                    'size' => $filesize,
                    'menu' => array(
                        array('text' => $this->xpdo->lexicon('file_remove'),'handler' => 'this.removeFile'),
                    ),
                );
            }
        }
        return $files;
    }
    /**
     * Get the name of this source type
     * @return string
     */
    public function getTypeName() {
        $this->xpdo->lexicon->load('source');
        return $this->xpdo->lexicon('source_type.file');
    }

    /**
     * Get the description of this source type
     * @return string
     */
    public function getTypeDescription() {
        $this->xpdo->lexicon->load('source');
        return $this->xpdo->lexicon('source_type.file_desc');
    }

    /**
     * Get the default properties for the filesystem media source type.
     * 
     * @return array
     */
    public function getDefaultProperties() {
        return array(
            'basePath' => array(
                'name' => 'basePath',
                'desc' => 'prop_file.basePath_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ),
            'basePathRelative' => array(
                'name' => 'basePathRelative',
                'desc' => 'prop_file.basePathRelative_desc',
                'type' => 'combo-boolean',
                'options' => '',
                'value' => true,
                'lexicon' => 'core:source',
            ),
            'baseUrl' => array(
                'name' => 'baseUrl',
                'desc' => 'prop_file.baseUrl_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ),
            'baseUrlRelative' => array(
                'name' => 'baseUrlRelative',
                'desc' => 'prop_file.baseUrlRelative_desc',
                'type' => 'combo-boolean',
                'options' => '',
                'value' => true,
                'lexicon' => 'core:source',
            ),
            'allowedFileTypes' => array(
                'name' => 'allowedFileTypes',
                'desc' => 'prop_file.allowedFileTypes_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ),
            'imageExtensions' => array(
                'name' => 'imageExtensions',
                'desc' => 'prop_file.imageExtensions_desc',
                'type' => 'textfield',
                'value' => 'jpg,jpeg,png,gif',
                'lexicon' => 'core:source',
            ),
            'thumbnailType' => array(
                'name' => 'thumbnailType',
                'desc' => 'prop_file.thumbnailType_desc',
                'type' => 'list',
                'options' => array(
                    array('name' => 'PNG','value' => 'png'),
                    array('name' => 'JPG','value' => 'jpg'),
                    array('name' => 'GIF','value' => 'gif'),
                ),
                'value' => 'png',
                'lexicon' => 'core:source',
            ),
            'thumbnailQuality' => array(
                'name' => 'thumbnailQuality',
                'desc' => 'prop_s3.thumbnailQuality_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => 90,
                'lexicon' => 'core:source',
            ),
            'skipFiles' => array(
                'name' => 'skipFiles',
                'desc' => 'prop_file.skipFiles_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '.svn,.git,_notes,nbproject,.idea,.DS_Store',
                'lexicon' => 'core:source',
            ),
        );
    }

    /**
     * Prepare the output values for image/file TVs by prefixing the baseUrl property to them
     *
     * @param string $value
     * @return string
     */
    public function prepareOutputUrl($value) {
        $properties = $this->getPropertyList();
        if (!empty($properties['baseUrl'])) {
            $value = $properties['baseUrl'].$value;
            if (isset($properties['baseUrlRelative']) && !empty($properties['baseUrlRelative'])) {
                $value = $this->xpdo->context->getOption('base_url',null,MODX_BASE_URL).$value;
            }
        }
        return $value;
    }


    /**
     * Get the base path for this source. Only applicable to sources that are streams.
     * 
     * @param string $object An optional file to find the base path of
     * @return string
     */
    public function getBasePath($object = '') {
        $bases = $this->getBases($object);
        return $bases['pathAbsolute'];
    }

    /**
     * Get the base URL for this source. Only applicable to sources that are streams.
     * 
     * @param string $object An optional object to find the base url of
     * @return string
     */
    public function getBaseUrl($object = '') {
        $bases = $this->getBases($object);
        return $bases['urlAbsolute'];
    }

    /**
     * Get the absolute URL for a specified object. Only applicable to sources that are streams.
     * 
     * @param string $object
     * @return string
     */
    public function getObjectUrl($object = '') {
        return $this->getBaseUrl().$object;
    }
}