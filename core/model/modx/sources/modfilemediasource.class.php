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

use League\Flysystem\Adapter\Local;

/**
 * Implements a file-system-based media source, allowing manipulation and management of files on the server's
 * location. Supports basePath and baseUrl parameters, similar to Revolution 2.1 and prior's filemanager_* settings.
 *
 * @package modx
 * @subpackage sources
 */
class modFileMediaSource extends modMediaSource
{

    protected $visibility_files = true;
    protected $visibility_dirs = true;


    /**
     * @return bool
     */
<<<<<<< HEAD
    public function initialize()
    {
        parent::initialize();
        try {
            $localAdapter = new Local($this->getBasePath());
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,
                $this->xpdo->lexicon('source_err_init', ['source' => $this->get('name')]) . ' ' . $e->getMessage());
=======
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

        /* check to see if the new resource already exists */
        if (file_exists($newPath)) {
            if (is_dir($newPath)) {
                $this->addError('name',$this->xpdo->lexicon('file_folder_err_ae'));
                return false;
            }
            $this->addError('name',sprintf($this->xpdo->lexicon('file_err_ae'),$newName));
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
>>>>>>> bbb29fa806348f56d8d7d2f5fb9e55e70da1ea7d

            return false;
        }
        $this->loadFlySystem($localAdapter);

        return true;
    }


    /**
     * Get the name of this source type
     *
     * @return string
     */
    public function getTypeName()
    {
        $this->xpdo->lexicon->load('source');

        return $this->xpdo->lexicon('source_type.file');
    }


    /**
     * Get the description of this source type
     *
     * @return string
     */
    public function getTypeDescription()
    {
        $this->xpdo->lexicon->load('source');

        return $this->xpdo->lexicon('source_type.file_desc');
    }


    /**
     * Get the default properties for the filesystem media source type.
     *
     * @return array
     */
    public function getDefaultProperties()
    {
        return [
            'basePath' => [
                'name' => 'basePath',
                'desc' => 'prop_file.basePath_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ],
            'basePathRelative' => [
                'name' => 'basePathRelative',
                'desc' => 'prop_file.basePathRelative_desc',
                'type' => 'combo-boolean',
                'options' => '',
                'value' => true,
                'lexicon' => 'core:source',
            ],
            'baseUrl' => [
                'name' => 'baseUrl',
                'desc' => 'prop_file.baseUrl_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ],
            'baseUrlRelative' => [
                'name' => 'baseUrlRelative',
                'desc' => 'prop_file.baseUrlRelative_desc',
                'type' => 'combo-boolean',
                'options' => '',
                'value' => true,
                'lexicon' => 'core:source',
            ],
            'allowedFileTypes' => [
                'name' => 'allowedFileTypes',
                'desc' => 'prop_file.allowedFileTypes_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ],
            'imageExtensions' => [
                'name' => 'imageExtensions',
                'desc' => 'prop_file.imageExtensions_desc',
                'type' => 'textfield',
                'value' => 'jpg,jpeg,png,gif,svg',
                'lexicon' => 'core:source',
            ],
            'thumbnailType' => [
                'name' => 'thumbnailType',
                'desc' => 'prop_file.thumbnailType_desc',
                'type' => 'list',
                'options' => [
                    ['name' => 'PNG', 'value' => 'png'],
                    ['name' => 'JPG', 'value' => 'jpg'],
                    ['name' => 'GIF', 'value' => 'gif'],
                ],
                'value' => 'png',
                'lexicon' => 'core:source',
            ],
            'thumbnailQuality' => [
                'name' => 'thumbnailQuality',
                'desc' => 'prop_s3.thumbnailQuality_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => 90,
                'lexicon' => 'core:source',
            ],
            'visibility' => [
                'name' => 'visibility',
                'desc' => 'prop_file.visibility_desc',
                'type' => 'modx-combo-visibility',
                'options' => '',
                'value' => 'public',
                'lexicon' => 'core:source',
            ],
            'skipFiles' => [
                'name' => 'skipFiles',
                'desc' => 'prop_file.skipFiles_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '.svn,.git,_notes,nbproject,.idea,.DS_Store',
                'lexicon' => 'core:source',
            ],
        ];
    }


    /**
     * Prepare the output values for image/file TVs by prefixing the baseUrl property to them
     *
     * @param string $value
     *
     * @return string
     */
    public function prepareOutputUrl($value)
    {
        $properties = $this->getPropertyList();
        if (!empty($properties['baseUrl'])) {
            $value = $properties['baseUrl'] . $value;
        }

        return $value;
    }


    /**
     * Get the base path for this source. Only applicable to sources that are streams.
     *
     * @param string $object An optional file to find the base path of
     *
     * @return string
     */
    public function getBasePath($object = '')
    {
        $bases = $this->getBases($object);

        return $bases['pathAbsolute'];
    }


    /**
     * Get the base URL for this source. Only applicable to sources that are streams.
     *
     * @param string $object An optional object to find the base url of
     *
     * @return string
     */
    public function getBaseUrl($object = '')
    {
        $bases = $this->getBases($object);

        return $bases['urlAbsolute'];
    }


    /**
     * Get the absolute URL for a specified object. Only applicable to sources that are streams.
     *
     * @param string $object
     *
     * @return string
     */
    public function getObjectUrl($object = '')
    {
        return $this->getBaseUrl() . $object;
    }
}
