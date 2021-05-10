<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
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
        $return = parent::initialize();
        $options = array();
        if (!$this->ctx) {
            $this->ctx =& $this->xpdo->context;
        }
        $options['context'] = $this->ctx->get('key');
        $this->fileHandler = $this->xpdo->getService('fileHandler','modFileHandler', '',$options);
        return $return;
    }

    /**
     * Get base paths/urls and sanitize incoming paths
     *
     * @param string $path A path to the active directory
     * @return array
     */
    public function getBases($path = '') {
        $properties = $this->getProperties();
        $bases = array();
        $path = $this->fileHandler->sanitizePath($path);
        $bases['path'] = $properties['basePath']['value'];
        $bases['pathIsRelative'] = false;
        if (!empty($properties['basePathRelative']['value'])) {
            $realpath = realpath($this->ctx->getOption('base_path', MODX_BASE_PATH) . $bases['path']);
            $bases['pathAbsolute'] = ($realpath !== false) ? $realpath. '/' : '';
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
        return 'system/file/edit';
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
        $hideTooltips = !empty($properties['hideTooltips']) && $properties['hideTooltips'] != 'false' ? true : false;
        $editAction = $this->getEditActionId();

        $imagesExts = $this->getOption('imageExtensions',$properties,'jpg,jpeg,png,gif,svg');
        $imagesExts = explode(',',$imagesExts);
        $skipFiles = $this->getOption('skipFiles',$properties,'.svn,.git,_notes,nbproject,.idea,.DS_Store');
        $skipFiles = explode(',',$skipFiles);
        if ($this->xpdo->getParser()) {
            $this->xpdo->parser->processElementTags('',$skipFiles,true,true);
        }
        $skipFiles[] = '.';
        $skipFiles[] = '..';

        $allowedExtensions = $this->getOption('allowedFileTypes', $properties, '');
        if (is_string($allowedExtensions)) {
            if (empty($allowedExtensions)) {
                $allowedExtensions = array();
            } else {
                $allowedExtensions = array_map("trim",explode(',', $allowedExtensions));
            }
        }

        $canSave = $this->checkPolicy('save');
        $canRemove = $this->checkPolicy('remove');
        $canCreate = $this->checkPolicy('create');

        $directories = array();
        $dirnames = array();
        $files = array();
        $filenames = array();

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

                $dirnames[] = strtoupper($fileName);
                $directories[$fileName] = array(
                    'id' => $bases['urlRelative'].rtrim($fileName,'/').'/',
                    'text' => $fileName,
                    'cls' => implode(' ',$cls),
                    'iconCls' => 'icon icon-folder',
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
                $ext = pathinfo($filePathName, PATHINFO_EXTENSION);
                $ext = $useMultibyte ? mb_strtolower($ext, $encoding) : strtolower($ext);
                if (!empty($allowedExtensions) && !in_array($ext, $allowedExtensions)) {
                    continue;
                }

                $cls = array();

                if (!empty($properties['currentFile']) && rawurldecode($properties['currentFile']) == $fullPath.$fileName && $properties['currentAction'] == $editAction) {
                    $cls[] = 'active-node';
                }

                if ($this->hasPermission('file_remove') && $canRemove) $cls[] = 'premove';
                if ($this->hasPermission('file_update') && $canSave) $cls[] = 'pupdate';

                $encFile = rawurlencode($fullPath.$fileName);
                $page = !empty($editAction) ? '?a='.$editAction.'&file='.rawurlencode($bases['urlRelative'].$fileName).'&wctx='.$this->ctx->get('key').'&source='.$this->get('id') : null;
                $url = $bases['urlRelative'] . $fileName;

                /* get relative url from manager/ */
                $fromManagerUrl = $bases['url'].trim(str_replace('//','/',$path.$fileName),'/');
                $fromManagerUrl = ($bases['urlIsRelative'] ? '../' : '').$fromManagerUrl;

                $filenames[] = strtoupper($fileName);
                $files[$fileName] = array(
                    'id' => $bases['urlRelative'].$fileName,
                    'text' => $fileName,
                    'cls' => implode(' ',$cls),
                    'iconCls' => 'icon icon-file icon-'.$ext . ($file->isWritable() ? '' : ' icon-lock'),
                    'type' => 'file',
                    'leaf' => true,
                    // 'qtip' => in_array($ext,$imagesExts) ? '<img src="'.$fromManagerUrl.'" alt="'.$fileName.'" />' : '',
                    'page' => $this->fileHandler->isBinary($filePathName) ? null : $page,
                    'perms' => $octalPerms,
                    'path' => $bases['pathAbsoluteWithPath'].$fileName,
                    'pathRelative' => $bases['pathRelative'].$fileName,
                    'directory' => $bases['path'],
                    'url' => $bases['url'].$url,
                    'urlAbsolute' => $bases['urlAbsoluteWithPath'].ltrim($fileName,'/'),
                    'file' => $encFile,
                    'menu' => array(),
                );
                $files[$fileName]['menu'] = array('items' => $this->getListContextMenu($file,$files[$fileName]));

                // trough tree config we can request a tree without image-preview tooltips, don't do any work if not necessary
                if (!$hideTooltips) {

                    $files[$fileName]['qtip'] = '';

                    if (in_array($ext, $imagesExts)) {

                        $modAuth = $this->xpdo->user->getUserToken($this->xpdo->context->get('key'));

                        $preview = true;
                        $imageWidth = $this->ctx->getOption('filemanager_image_width', 400);
                        $imageHeight = $this->ctx->getOption('filemanager_image_height', 300);
                        $thumbnailType = $this->getOption('thumbnailType', $properties, 'png');
                        $thumbnailQuality = $this->getOption('thumbnailQuality', $properties, 90);

                        if ($ext == 'svg') {
                            $svgString = @file_get_contents($bases['pathAbsoluteWithPath'].$fileName);
                            preg_match('/(<svg[^>]*\swidth=")([\d\.]+)([a-z]*)"/si', $svgString, $svgWidth);
                            preg_match('/(<svg[^>]*\sheight=")([\d\.]+)([a-z]*)"/si', $svgString, $svgHeight);
                            preg_match('/(<svg[^>]*\sviewBox=")([\d\.]+(?:,|\s)[\d\.]+(?:,|\s)([\d\.]+)(?:,|\s)([\d\.]+))"/si', $svgString, $svgViewbox);
                            if (!empty($svgViewbox)) {
                                // get width and height from viewbox attribute
                                $imageWidth = round($svgViewbox[3]);
                                $imageHeight = round($svgViewbox[4]);
                            } elseif (!empty($svgWidth) && !empty($svgHeight)) {
                                // get width and height from width and height attributes
                                $imageWidth = round($svgWidth[2]);
                                $imageHeight = round($svgHeight[2]);
                            }
                            $image = $bases['urlAbsolute'] . urldecode($url);
                        } else {
                            $size = @getimagesize($bases['pathAbsoluteWithPath'].$fileName);
                            if (is_array($size) && $size[0] > 0 && $size[1] > 0) {
                                // get original image size for proportional scaling
                                if ($size[0] > $size[1]) {
                                    // landscape
                                    $imageQueryWidth = $size[0] >= $imageWidth ? $imageWidth : $size[0];
                                    $imageQueryHeight = 0;
                                    $imageWidth = $imageQueryWidth;
                                    $imageHeight = round($size[1] * ($imageQueryWidth / $size[0]));
                                } else {
                                    // portrait or square
                                    $imageQueryWidth = 0;
                                    $imageQueryHeight = $size[1] >= $imageHeight ? $imageHeight : $size[1];
                                    $imageWidth = round($size[0] * ($imageQueryHeight / $size[1]));
                                    $imageHeight = $imageQueryHeight;
                                }
                                $imageQuery = http_build_query(array(
                                    'src' => $bases['urlRelative'].$fileName,
                                    'w' => $imageQueryWidth,
                                    'h' => $imageQueryHeight,
                                    'HTTP_MODAUTH' => $modAuth,
                                    'f' => $thumbnailType,
                                    'q' => $thumbnailQuality,
                                    'wctx' => $this->ctx->get('key'),
                                    'source' => $this->get('id'),
                                    't' => $file->getMTime(),
                                ));
                                $image = $this->ctx->getOption('connectors_url', MODX_CONNECTORS_URL).'system/phpthumb.php?'.rawurldecode($imageQuery);
                            } else {
                                $preview = false;
                                $this->xpdo->log(modX::LOG_LEVEL_ERROR,'Thumbnail could not be created for file: '.$bases['pathAbsoluteWithPath'].$fileName);
                            }
                        }

                        if ($preview) {
                            $files[$fileName]['qtip'] = '<img src="'.$image.'" width="'.$imageWidth.'" height="'.$imageHeight.'" alt="'.$fileName.'" />';
                        }

                    }

                }

            }
        }

        $ls = array();
        /* now sort files/directories */
        array_multisort($dirnames, SORT_ASC, SORT_STRING, $directories);
        // uksort($directories, 'strnatcasecmp');
        foreach ($directories as $dir) {
            $ls[] = $dir;
        }
        array_multisort($filenames, SORT_ASC, SORT_STRING, $files);
        // uksort($files, 'strnatcasecmp');
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
                    $menu[] = array(
                        'text' => $this->xpdo->lexicon('quick_update_file'),
                        'handler' => 'this.quickUpdateFile',
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

                $menu[] = array(
                    'text' => $this->xpdo->lexicon('file_copy_path'),
                    'handler' => 'this.copyRelativePath',
                );
            }
            if ($this->hasPermission('file_unpack') && $canView && pathinfo($file->getFilename(), PATHINFO_EXTENSION) === 'zip') {
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('file_download_unzip'),
                    'handler' => 'this.unpackFile',
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

            $menu[] = array(
                'text' => $this->xpdo->lexicon('file_folder_copy_path'),
                'handler' => 'this.copyRelativePath',
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
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('quick_create_file'),
                    'handler' => 'this.quickCreateFile',
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

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerDirCreate',array(
            'directory' => $newDirectoryPath,
            'source' => &$this,
        ));

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

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerDirRemove',array(
            'directory' => $path,
            'source' => &$this,
        ));

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
        $newPath = dirname($oldPath).'/'.$newPath;

        /* check to see if the new resource already exists */
        if (file_exists($newPath)) {
            $this->addError('name',$this->xpdo->lexicon('file_folder_err_ae'));
            return false;
        }

        /* rename the dir */
        if (!$oldDirectory->rename($newPath)) {
            $this->addError('name',$this->xpdo->lexicon('file_folder_err_rename'));
            return false;
        }

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerDirRename',array(
            'directory' => $newPath,
            'source' => &$this,
        ));

        $this->xpdo->logManagerAction('directory_rename','',$oldDirectory->getPath());
        return true;
    }

    /**
     * Check that the filename has a file type extension that is allowed
     *
     * @param $filename
     * @return bool
     */
    public function checkFiletype($filename) {
        if ($this->getOption('allowedFileTypes')) {
            $allowedFileTypes = $this->getOption('allowedFileTypes');
            $allowedFileTypes = (!is_array($allowedFileTypes)) ? array_map("trim",explode(',', $allowedFileTypes)) : $allowedFileTypes;
        } else {
            $allowedFiles = $this->xpdo->getOption('upload_files') ? array_map("trim",explode(',', $this->xpdo->getOption('upload_files'))) : array();
            $allowedImages = $this->xpdo->getOption('upload_images') ? array_map("trim",explode(',', $this->xpdo->getOption('upload_images'))) : array();
            $allowedMedia = $this->xpdo->getOption('upload_media') ? array_map("trim",explode(',', $this->xpdo->getOption('upload_media'))) : array();
            $allowedFlash = $this->xpdo->getOption('upload_flash') ? array_map("trim",explode(',', $this->xpdo->getOption('upload_flash'))): array();
            $allowedFileTypes = array_unique(array_merge($allowedFiles, $allowedImages, $allowedMedia, $allowedFlash));
            $this->setOption('allowedFileTypes', $allowedFileTypes);
        }

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        if (!empty($allowedFileTypes) && !in_array($ext, $allowedFileTypes)) {
            $this->addError('path', $this->xpdo->lexicon('file_err_ext_not_allowed', array(
                'ext' => $ext,
            )));

            return false;
        }
        return true;
    }

    /**
     * Check that an object (directory, file) exists
     *
     * @param string $objectPath The object path to check
     * @param string $objectName The object name displayed in the error message
     * @return bool
     */
    protected function checkObjectExist($objectPath, $objectName) {
        if (file_exists($objectPath)) {
            if (is_dir($objectPath)) {
                $this->addError('name', $this->xpdo->lexicon('file_folder_err_ae'));
                return true;
            }
            $this->addError('name', sprintf($this->xpdo->lexicon('file_err_ae'), htmlentities($objectName, ENT_QUOTES, 'UTF-8')));
            return true;
        }
        return false;
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

        if (!$this->checkFiletype($newName)) {
            return false;
        }

        /* sanitize new path */
        $newPath = $this->fileHandler->sanitizePath($newName);
        $newPath = dirname($oldPath).'/'.$newPath;

        if ($this->checkObjectExist($newPath,$newName)) {
            return false;
        }

        /* rename the file */
        if (!$oldFile->rename($newPath)) {
            $this->addError('name',$this->xpdo->lexicon('file_folder_err_rename'));
            return false;
        }

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerFileRename',array(
            'path' => $newPath,
            'source' => &$this,
        ));

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
        $imageExtensions = $this->getOption('imageExtensions',$properties,'jpg,jpeg,png,gif,svg');
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
        if (!$this->checkFiletype($fullPath)) {
            return false;
        }
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

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerFileRemove',array(
            'path' => $fullPath,
            'source' => &$this,
        ));

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
        if (!$this->checkFiletype($fullPath)) {
            return false;
        }

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

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerFileUpdate',array(
            'path' => $fullPath,
            'source' => &$this,
        ));

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

        if (!$this->checkFiletype($fullPath)) {
            return false;
        }

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

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerFileCreate',array(
            'path' => $fullPath,
            'source' => &$this,
        ));

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

        $maxFileSize = $this->xpdo->getOption('upload_maxsize',null,1048576);

        $mode = $this->fileHandler->modx->getOption('new_file_permissions');
        if ($mode) {
            $mode = octdec($mode);
        }

        /* loop through each file and upload */
        foreach ($objects as $file) {
            /* invoke event */
            $this->xpdo->invokeEvent('OnFileManagerBeforeUpload', array(
                'files' => &$objects,
                'file' => &$file,
                'directory' => $container,
                'source' => &$this,
            ));

            if ($file['error'] != 0) continue;
            if (empty($file['name'])) continue;

            if (!$this->checkFiletype($file['name'])) {
                continue;
            }

            $size = filesize($file['tmp_name']);

            if ($size > $maxFileSize) {
                $this->addError('path',$this->xpdo->lexicon('file_err_too_large',array(
                    'size' => $size,
                    'allowed' => $maxFileSize,
                )));
                continue;
            }

            $newPath = $this->fileHandler->sanitizePath($file['name']);
            $newPath = $directory->getPath().$newPath;

            $checkAlreadyExists = (bool)$this->xpdo->getOption('upload_check_exists', null, true);
            if ($checkAlreadyExists && $this->checkObjectExist($newPath,$file['name'])) {
                return false;
            }

            if (!move_uploaded_file($file['tmp_name'],$newPath)) {
                $this->addError('path',$this->xpdo->lexicon('file_err_upload'));
                continue;
            }

            if ($mode) {
                @chmod($newPath, $mode);
            }
        }

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerUpload',array(
            'files' => &$objects,
            'directory' => $container,
            'source' => &$this,
        ));

        $this->xpdo->logManagerAction('file_upload','',$directory->getPath());

        return !$this->hasErrors();
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

        if (!$directory->isValidMode($mode)) {
            $this->addError('mode',$this->xpdo->lexicon('file_err_chmod_invalid'));
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

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerMoveObject',array(
            'from' => $fromObject->getPath(),
            'to' => $toObject->getPath(),
            'source' => &$this,
        ));

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
        $imageExtensions = $this->getOption('imageExtensions',$properties,'jpg,jpeg,png,gif,svg');
        $imageExtensions = explode(',',$imageExtensions);
        $use_multibyte = $this->ctx->getOption('use_multibyte', false);
        $encoding = $this->ctx->getOption('modx_charset', 'UTF-8');
        $allowedFileTypes = $this->getOption('allowedFileTypes', $properties, '');
        $editAction = $this->getEditActionId();
        if (is_string($allowedFileTypes)) {
            if (empty($allowedFileTypes)) {
                $allowedFileTypes = array();
            } else {
                $allowedFileTypes = explode(',', $allowedFileTypes);
            }
        }
        $thumbnailType = $this->getOption('thumbnailType',$properties,'png');
        $thumbnailQuality = $this->getOption('thumbnailQuality',$properties,90);
        $skipFiles = $this->getOption('skipFiles',$properties,'.svn,.git,_notes,nbproject,.idea,.DS_Store');
        $skipFiles = explode(',',$skipFiles);
        $skipFiles[] = '.';
        $skipFiles[] = '..';

        /* iterate */
        $files = array();
        $filenames = array();

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

                if (!empty($allowedFileTypes) && !in_array($fileExtension, $allowedFileTypes)) {
                    continue;
                }

                $filesize = @filesize($filePathName);
                $url = rawurlencode(ltrim($dir.$fileName,'/'));
                $page = !empty($editAction) ? '?a='.$editAction.'&file='.rawurlencode($bases['urlRelative'].$fileName).'&wctx='.$this->ctx->get('key').'&source='.$this->get('id') : null;

                /* get thumbnail */
                $preview = 0;

                if (in_array($fileExtension,$imageExtensions)) {
                    $preview = 1;
                    $imageWidth = $this->ctx->getOption('filemanager_image_width', 800);
                    $imageHeight = $this->ctx->getOption('filemanager_image_height', 600);
                    $thumbWidth = $this->ctx->getOption('filemanager_thumb_width', 100);
                    $thumbHeight = $this->ctx->getOption('filemanager_thumb_height', 80);

                    $size = array($imageWidth, $imageHeight);

                    if ($fileExtension == 'svg') {
                        $svgString = @file_get_contents($filePathName);
                        preg_match('/(<svg[^>]*\swidth=")([\d\.]+)([a-z]*)"/si', $svgString, $svgWidth);
                        preg_match('/(<svg[^>]*\sheight=")([\d\.]+)([a-z]*)"/si', $svgString, $svgHeight);
                        preg_match('/(<svg[^>]*\sviewBox=")([\d\.]+(?:,|\s)[\d\.]+(?:,|\s)([\d\.]+)(?:,|\s)([\d\.]+))"/si', $svgString, $svgViewbox);
                        if (!empty($svgViewbox)) {
                            // get width and height from viewbox attribute
                            $size[0] = round($svgViewbox[3]);
                            $size[1] = round($svgViewbox[4]);
                        } elseif (!empty($svgWidth) && !empty($svgHeight)) {
                            // get width and height from width and height attributes
                            $size[0] = round($svgWidth[2]);
                            $size[1] = round($svgHeight[2]);
                        }
                        // proportional scaling of image and thumb
                        if ($size[0] > $size[1]) {
                            // landscape
                            $imageWidth = $size[0] >= $imageWidth ? $imageWidth : $size[0];
                            $imageHeight = round($size[1] * ($imageWidth / $size[0]));
                            $thumbWidth = $size[0] >= $thumbWidth ? $thumbWidth : $size[0];
                            $thumbHeight = round($size[1] * ($thumbWidth / $size[0]));
                        } else {
                            // portrait or square
                            $imageHeight = $size[1] >= $imageHeight ? $imageHeight : $size[1];
                            $imageWidth = round($size[0] * ($imageHeight / $size[1]));
                            $thumbHeight = $size[1] >= $thumbHeight ? $thumbHeight : $size[1];
                            $thumbWidth = round($size[0] * ($thumbHeight / $size[1]));
                        }
                        $image = $thumb = $bases['urlAbsolute'].rawurldecode($url);
                    } else {
                        $size = @getimagesize($filePathName);
                        if (is_array($size) && $size[0] > 0 && $size[1] > 0) {
                            // proportional scaling of image and thumb
                            if ($size[0] > $size[1]) {
                                // landscape
                                $imageQueryWidth = $size[0] >= $imageWidth ? $imageWidth : $size[0];
                                $imageQueryHeight = 0;
                                $imageWidth = $imageQueryWidth;
                                $imageHeight = round($size[1] * ($imageQueryWidth / $size[0]));
                                $thumbQueryWidth = $size[0] >= $thumbWidth ? $thumbWidth : $size[0];
                                $thumbQueryHeight = 0;
                                $thumbWidth = $thumbQueryWidth;
                                $thumbHeight = round($size[1] * ($thumbQueryWidth / $size[0]));
                            } else {
                                // portrait or square
                                $imageQueryWidth = 0;
                                $imageQueryHeight = $size[1] >= $imageHeight ? $imageHeight : $size[1];
                                $imageWidth = round($size[0] * ($imageQueryHeight / $size[1]));
                                $imageHeight = $imageQueryHeight;
                                $thumbQueryWidth = 0;
                                $thumbQueryHeight = $size[1] >= $thumbHeight ? $thumbHeight : $size[1];
                                $thumbWidth = round($size[0] * ($thumbQueryHeight / $size[1]));
                                $thumbHeight = $thumbQueryHeight;
                            }
                            $imageQuery = http_build_query(array(
                                'src' => $url,
                                'w' => $imageQueryWidth,
                                'h' => $imageQueryHeight,
                                'HTTP_MODAUTH' => $modAuth,
                                'f' => $thumbnailType,
                                'q' => $thumbnailQuality,
                                'wctx' => $this->ctx->get('key'),
                                'source' => $this->get('id'),
                                't' => $file->getMTime(),
                            ));
                            $image = $this->ctx->getOption('connectors_url', MODX_CONNECTORS_URL).'system/phpthumb.php?'.rawurldecode($imageQuery);
                            $thumbQuery = http_build_query(array(
                                'src' => $url,
                                'w' => $thumbQueryWidth,
                                'h' => $thumbQueryHeight,
                                'HTTP_MODAUTH' => $modAuth,
                                'f' => $thumbnailType,
                                'q' => $thumbnailQuality,
                                'wctx' => $this->ctx->get('key'),
                                'source' => $this->get('id'),
                                't' => $file->getMTime(),
                            ));
                            $thumb = $this->ctx->getOption('connectors_url', MODX_CONNECTORS_URL).'system/phpthumb.php?'.rawurldecode($thumbQuery);
                        } else {
                            $this->xpdo->log(modX::LOG_LEVEL_ERROR,'Thumbnail could not be created for file: '.$filePathName);
                            $preview = 0;
                        }
                    }
                }
                if ($preview == 0) {
                    $size = null;
                    $thumb = $image = $this->ctx->getOption('manager_url', MODX_MANAGER_URL).'templates/default/images/restyle/nopreview.jpg';
                    $thumbWidth = $imageWidth = $this->ctx->getOption('filemanager_thumb_width', 100);
                    $thumbHeight = $imageHeight = $this->ctx->getOption('filemanager_thumb_height', 80);
                }
                $octalPerms = substr(sprintf('%o', $file->getPerms()), -4);

                $filenames[] = strtoupper($fileName);
                $files[$fileName] = array(
                    'id' => rawurlencode($bases['urlAbsoluteWithPath'].$fileName),
                    'name' => $fileName,
                    'cls' => 'icon-'.$fileExtension,
                    'image' => $image,
                    'image_width' => is_array($size) && $size[0] > 0 ? $size[0] : $imageWidth,
                    'image_height' => is_array($size) && $size[1] > 0 ? $size[1] : $imageHeight,
                    'thumb' => $thumb,
                    'thumb_width' => $thumbWidth,
                    'thumb_height' => $thumbHeight,
                    'url' => ltrim($dir.$fileName,'/'),
                    'relativeUrl' => ltrim($dir.$fileName,'/'),
                    'fullRelativeUrl' => rtrim($bases['url']).ltrim($dir.$fileName,'/'),
                    'ext' => $fileExtension,
                    'pathname' => str_replace('//','/',$filePathName),
                    'pathRelative' => $bases['pathRelative'].$fileName,
                    'lastmod' => $file->getMTime(),
                    'preview' => $preview,
                    'disabled' => false,
                    'perms' => $octalPerms,
                    'leaf' => true,
                    'page' => $this->fileHandler->isBinary($filePathName) ? null : $page,
                    'size' => $filesize,
                    'menu' => array(),
                );
                $files[$fileName]['menu'] = $this->getListContextMenu($file, $files[$fileName]);
            }
        }

        $ls = array();
        array_multisort($filenames, SORT_ASC, SORT_STRING, $files);

        foreach ($files as $file) {
            $ls[] = $file;
        }

        return $ls;
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
                'value' => 'jpg,jpeg,png,gif,svg',
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
