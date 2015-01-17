<?php
/**
 * @package modx
 * @subpackage sources
 */
require_once MODX_CORE_PATH . 'model/modx/sources/modmediasource.class.php';
/**
 * Implements a Google Cloud Storage-based media source, allowing basic
 * manipulation, uploading and URL-retrieval of resources in a specified bucket.
 * 
 * @package modx
 * @subpackage sources
 */
class modGcsMediaSource extends modMediaSource implements modMediaSourceInterface {
    /** @var Google_Client $client */
    public $client;
    /** @var Google_Service_Storage $driver */
    public $driver;
    /** @var string $bucket */
    public $bucket;

    /**
     * Initializes GCS media class, getting the GCS driver and loading the bucket
     * @return boolean
     */
    public function initialize() {
        parent::initialize();
        $properties = $this->getPropertyList();
        if (!defined('GCS_APP_NAME')) {
            define('GCS_APP_NAME',$this->xpdo->getOption('gcs.app_name',$properties,''));
            define('GCS_EMAIL_ADDRESS',$this->xpdo->getOption('gcs.email_address',$properties,''));
            $key = $this->xpdo->getOption('gcs.private_key_file',$properties,'');
            $key = str_replace(array('{assets_path}','{core_path}','{base_path}'), array(MODX_ASSETS_PATH,MODX_CORE_PATH,MODX_BASE_PATH),$key);
            define('GCS_PRIMARY_KEY_FILE',$key);
            define('GCS_CLIENT_ID',$this->xpdo->getOption('gcs.client_id',$properties,''));
            define('GCS_DEFAULT_BUCKET_NAME',$this->xpdo->getOption('gcs.default_bucket_name',$properties,''));
            $scopes = @explode(',',$this->xpdo->getOption('gcs.scopes',$properties,''));
            define('GCS_SCOPES',serialize($scopes));
        }
        include_once $this->xpdo->getOption('core_path',null,MODX_CORE_PATH).'model/google/autoload.php';
        if (!$this->getDriver()) {
            return false;
        }
        $this->setBucket(GCS_DEFAULT_BUCKET_NAME);
        return true;
    }

    /**
     * Get the name of this source type
     * @return string
     */
    public function getTypeName() {
        $this->xpdo->lexicon->load('source');
        return $this->xpdo->lexicon('source_type.gcs');
    }
    /**
     * Get the description of this source type
     * @return string
     */
    public function getTypeDescription() {
        $this->xpdo->lexicon->load('source');
        return $this->xpdo->lexicon('source_type.gcs_desc');
    }

    /**
     * Gets the Google Cloud Storage class instance
     * @return Google_Service_Storage
     */
    public function getDriver() {
        if (empty($this->driver)) {
            try {
                $this->client = new Google_Client();
                $this->client->setApplicationName(GCS_APP_NAME);
                $this->client->setClientId(GCS_CLIENT_ID);
                $this->client->setScopes(unserialize(GCS_SCOPES));
                $this->driver = new Google_Service_Storage($this->client);

                if (isset($_SESSION['service_token'])) {
                    $this->client->setAccessToken($_SESSION['service_token']);
                }
                if (!file_exists(GCS_PRIMARY_KEY_FILE)) {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, __METHOD__ . ' missing the location of primary key file, given: ' . GCS_PRIMARY_KEY_FILE);
                    return false;
                }
                $key = file_get_contents(GCS_PRIMARY_KEY_FILE);
                $cred = new Google_Auth_AssertionCredentials(
                        GCS_EMAIL_ADDRESS
                        ,unserialize(GCS_SCOPES)
                        ,$key
                );
                $this->client->setAssertionCredentials($cred);
                if ($this->client->getAuth()->isAccessTokenExpired()) {
                    $this->client->getAuth()->refreshTokenWithAssertion($cred);
                }
                $_SESSION['service_token'] = $this->client->getAccessToken();
            } catch (Exception $ex) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, __METHOD__ . ' : ' . $ex->getMessage() . "\n" . $ex->getTraceAsString());
            }
        }
        return $this->driver;
    }

    /**
     * Set the bucket for the connection to Google Cloud Storage
     * @param string $bucket
     * @return void
     */
    public function setBucket($bucket) {
        $this->bucket = $bucket;
    }

    /**
     * Get a list of objects from within a bucket
     * @param string $dir
     * @return array
     */
    public function getGcsObjectList($dir) {
        $c['delimiter'] = '/';
        if (!empty($dir) && $dir != '/') { $c['prefix'] = $dir; }
        
        $list = array();
        try {
            $objects = $this->driver->objects->listObjects($this->bucket, $c);
        } catch (Exception $ex) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, __METHOD__ . ' : ' . $ex->getMessage() . "\n" . $ex->getTraceAsString());
            return array();
        }

        $folders = $objects->getPrefixes();
        if (!empty($folders)) {
            foreach ($folders as $folder) {
                $list[] = $folder;
            }
        }
        $files = $objects->getItems();
        if (!empty($files)) {
            foreach ($files as $file) {
                $list[] = $file->getName();
            }
        }
        return $list;
    }

    /**
     * Get the ID of the edit file action
     *
     * @return boolean|int
     */
    public function getEditAction() {
        return 'system/file/edit';
    }

    /**
     * Return an array of containers at this current level in the container structure. Used for the tree
     * navigation on the files tree.
     *
     * @param string $path
     * @return array
     */
    public function getContainerList($path) {
        $url = 'https://'.$this->bucket . '.storage.googleapis.com';
        $list = $this->getGcsObjectList($path);
        $editAction = $this->getEditAction();

        $useMultiByte = $this->ctx->getOption('use_multibyte', false);
        $encoding = $this->ctx->getOption('modx_charset', 'UTF-8');

        $directories = array();
        $dirnames = array();
        $files = array();
        $filenames = array();
        
        foreach ($list as $idx => $currentPath) {
            if ($currentPath == $path) continue;
            $fileName = basename($currentPath);
            $isDir = substr(strrev($currentPath),0,1) === '/';

            $extension = pathinfo($fileName,PATHINFO_EXTENSION);
            $extension = $useMultiByte ? mb_strtolower($extension,$encoding) : strtolower($extension);

            $relativePath = $currentPath == '/' ? $currentPath : str_replace($path,'',$currentPath);
            $slashCount = substr_count($relativePath,'/');
            if (($slashCount > 1 && $isDir) || ($slashCount > 0 && !$isDir)) {
                continue;
            }
            if ($isDir) {
                $dirnames[] = strtoupper($fileName);
                $directories[$currentPath] = array(
                    'id' => $currentPath,
                    'text' => $fileName,
                    'iconCls' => 'icon icon-folder',
                    'type' => 'dir',
                    'leaf' => false,
                    'path' => $currentPath,
                    'pathRelative' => $currentPath,
                    'perms' => '',
                );
                $directories[$currentPath]['menu'] = array('items' => $this->getListContextMenu($currentPath,$isDir,$directories[$currentPath]));
            } else {
                $page = '?a='.$editAction.'&file='.$currentPath.'&wctx='.$this->ctx->get('key').'&source='.$this->get('id');

                $isBinary = $this->isBinary($url.'/'.$currentPath);

                $cls = array();
                $cls[] = 'icon-'.$extension;
                if($isBinary) {
                    $cls[] = 'icon-lock';
                }

                if ($this->hasPermission('file_remove')) $cls[] = 'premove';
                if ($this->hasPermission('file_update')) $cls[] = 'pupdate';

                $filenames[] = strtoupper($fileName);
                $ext = pathinfo($url.'/'.$currentPath, PATHINFO_EXTENSION);
                $ext = $useMultibyte ? mb_strtolower($ext, $encoding) : strtolower($ext);
                $files[$currentPath] = array(
                    'id' => $currentPath,
                    'text' => $fileName,
                    'iconCls' => implode(' ', $cls),
                    'type' => 'file',
                    'leaf' => true,
                    'path' => $currentPath,
                    'page' => $isBinary ? null : $page,
                    'pathRelative' => $currentPath,
                    'directory' => $currentPath,
                    'url' => $url.'/'.$currentPath,
                    'urlAbsolute' => $url.'/'.$currentPath,
                    'file' => $currentPath,
                    'menu' => array(),
                );
                $files[$currentPath]['menu'] = array('items' => $this->getListContextMenu($currentPath,$isDir,$files[$currentPath]));
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
     * Get the context menu for when viewing the source as a tree
     * 
     * @param string $file
     * @param boolean $isDir
     * @param array $fileArray
     * @return array
     */
    public function getListContextMenu($file,$isDir,array $fileArray) {
        $menu = array();
        if (!$isDir) { /* files */
            if ($this->hasPermission('file_update')) {
                if ($fileArray['page'] != null) {
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
            if ($this->hasPermission('file_view')) {
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('file_download'),
                    'handler' => 'this.downloadFile',
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
            if ($this->hasPermission('file_create')) {
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('file_create'),
                    'handler' => 'this.createFile',
                );
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('quick_create_file'),
                    'handler' => 'this.quickCreateFile',
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
     * Return a detailed list of objects in a specific path. Used for thumbnails in the Browser.
     *
     * @param string $path
     * @return array
     */
    public function getObjectsInContainer($path) {
        $list = $this->getGcsObjectList($path);

        $modAuth = $this->xpdo->user->getUserToken($this->xpdo->context->get('key'));

        /* get default settings */
        $use_multibyte = $this->ctx->getOption('use_multibyte', false);
        $encoding = $this->ctx->getOption('modx_charset', 'UTF-8');
        $bucketUrl = 'https://'.$this->bucket . '.storage.googleapis.com/';
        $allowedFileTypes = $this->getOption('allowedFileTypes',$this->properties,'');
        $allowedFileTypes = !empty($allowedFileTypes) && is_string($allowedFileTypes) ? explode(',',$allowedFileTypes) : $allowedFileTypes;
        $imageExtensions = $this->getOption('imageExtensions',$this->properties,'jpg,jpeg,png,gif');
        $imageExtensions = explode(',',$imageExtensions);
        $thumbnailType = $this->getOption('thumbnailType',$this->properties,'png');
        $thumbnailQuality = $this->getOption('thumbnailQuality',$this->properties,90);
        $skipFiles = $this->getOption('skipFiles',$this->properties,'.svn,.git,_notes,.DS_Store');
        $skipFiles = explode(',',$skipFiles);
        $skipFiles[] = '.';
        $skipFiles[] = '..';

        /* iterate */
        $files = array();
        $filenames = array();

        foreach ($list as $object) {
            $objectUrl = $bucketUrl.trim($object,'/');
            $baseName = basename($object);
            $isDir = substr(strrev($object),0,1) == '/' ? true : false;
            if (in_array($object,$skipFiles)) continue;

            if (!$isDir) {
                $filenames[] = strtoupper($baseName);
                $fileArray = array(
                    'id' => $object,
                    'name' => $baseName,
                    'url' => $objectUrl,
                    'relativeUrl' => $objectUrl,
                    'fullRelativeUrl' => $objectUrl,
                    'pathname' => $objectUrl,
                    'size' => 0,
                    'leaf' => true,
                    'menu' => array(
                        array('text' => $this->xpdo->lexicon('file_remove'),'handler' => 'this.removeFile'),
                    ),
                );

                $fileArray['ext'] = pathinfo($baseName,PATHINFO_EXTENSION);
                $fileArray['ext'] = $use_multibyte ? mb_strtolower($fileArray['ext'],$encoding) : strtolower($fileArray['ext']);
                $fileArray['cls'] = 'icon-'.$fileArray['ext'];

                if (!empty($allowedFileTypes) && !in_array($fileArray['ext'],$allowedFileTypes)) continue;

                /* get thumbnail */
                if (in_array($fileArray['ext'],$imageExtensions)) {
                    $imageWidth = $this->ctx->getOption('filemanager_image_width', 400);
                    $imageHeight = $this->ctx->getOption('filemanager_image_height', 300);
                    $thumbHeight = $this->ctx->getOption('filemanager_thumb_height', 60);
                    $thumbWidth = $this->ctx->getOption('filemanager_thumb_width', 80);

                    $size = @getimagesize($objectUrl);
                    if (is_array($size)) {
                        $imageWidth = $size[0] > 800 ? 800 : $size[0];
                        $imageHeight = $size[1] > 600 ? 600 : $size[1];
                    }

                    /* ensure max h/w */
                    if ($thumbWidth > $imageWidth) $thumbWidth = $imageWidth;
                    if ($thumbHeight > $imageHeight) $thumbHeight = $imageHeight;

                    /* generate thumb/image URLs */
                    $thumbQuery = http_build_query(array(
                        'src' => $object,
                        'w' => $thumbWidth,
                        'h' => $thumbHeight,
                        'f' => $thumbnailType,
                        'q' => $thumbnailQuality,
                        'HTTP_MODAUTH' => $modAuth,
                        'wctx' => $this->ctx->get('key'),
                        'source' => $this->get('id'),
                    ));
                    $imageQuery = http_build_query(array(
                        'src' => $object,
                        'w' => $imageWidth,
                        'h' => $imageHeight,
                        'HTTP_MODAUTH' => $modAuth,
                        'f' => $thumbnailType,
                        'q' => $thumbnailQuality,
                        'wctx' => $this->ctx->get('key'),
                        'source' => $this->get('id'),
                    ));
                    $fileArray['thumb'] = $this->ctx->getOption('connectors_url', MODX_CONNECTORS_URL).'system/phpthumb.php?'.urldecode($thumbQuery);
                    $fileArray['image'] = $this->ctx->getOption('connectors_url', MODX_CONNECTORS_URL).'system/phpthumb.php?'.urldecode($imageQuery);

                } else {
                    $fileArray['thumb'] = $this->ctx->getOption('manager_url', MODX_MANAGER_URL).'templates/default/images/restyle/nopreview.jpg';
                    $fileArray['thumbWidth'] = $this->ctx->getOption('filemanager_thumb_width', 80);
                    $fileArray['thumbHeight'] = $this->ctx->getOption('filemanager_thumb_height', 60);
                }
                $files[] = $fileArray;
            }
        }

        array_multisort($filenames, SORT_ASC, SORT_STRING, $files);

        return $files;
    }

    /**
     * Create a container at the passed location with the passed name
     *
     * @param string $name
     * @param string $parentContainer
     * @return boolean
     */
    public function createContainer($name,$parentContainer) {
        $newPath = ltrim($parentContainer.rtrim($name,'/').'/', '/');
        /* check to see if folder already exists */
        try {
            if ($this->driver->objects->get($this->bucket,$newPath)) {
                $this->addError('file',$this->xpdo->lexicon('file_folder_err_ae').': '.$newPath);
                return false;
            }
        } catch (Exception $ex) {
            $this->addError('file',$ex->getMessage());
            return false;
        }

        /* create empty file that acts as folder */
        $postBody = new Google_Service_Storage_StorageObject();
        $postBody->setName($newPath);
        $postBody->setSize(0);
        $postBody->setContentType('text/plain');
        try {
            $created = $this->driver->objects->insert($this->bucket,$postBody,array(
                'predefinedAcl' => 'publicRead',
                'name' => $newPath
            ));
        } catch (Exception $ex) {
            $this->addError('file',$ex->getMessage());
            return false;
        }

        if (!$created) {
            $this->addError('name',$this->xpdo->lexicon('file_folder_err_create').$newPath);
            return false;
        }

        $this->xpdo->logManagerAction('directory_create','',$newPath);
        return true;
    }

    /**
     * Remove the specified container
     *
     * @param string $path
     * @return boolean
     */
    public function removeContainer($path) {
        $deleted = false;
        try {
            if (!$this->driver->objects->get($this->bucket,$path)) {
                $this->addError('file',$this->xpdo->lexicon('file_folder_err_ns').': '.$path);
                return false;
            }
            /* remove file from GCS */
            $deleted = $this->driver->objects->delete($this->bucket,$path);
        } catch (Exception $ex) {
            $this->addError('file',$ex->getMessage());
            return false;
        }
        /* log manager action */
        $this->xpdo->logManagerAction('directory_remove','',$path);

        return !empty($deleted);
    }

    /**
     * Create an object from a path
     *
     * @param string $objectPath
     * @param string $name
     * @param string $content
     * @return boolean|string
     */
    public function createObject($objectPath,$name,$content) {
        $created = false;
        /* check to see if file already exists */
        try {
            if ($this->driver->objects->get($this->bucket,$objectPath.$name,array('projection'=>'full'))) {
                $this->addError('file',sprintf($this->xpdo->lexicon('file_err_ae'),$objectPath.$name));
                return false;
            }
        } catch (Exception $ex) {}
        try {
            $temp = tempnam(sys_get_temp_dir(), 'gcs');
            $handle = fopen($temp, "r+b");
            fwrite($handle, $content);
            fseek($handle, 0);
            fclose($handle);
            if ($this->uploadFile($temp, $objectPath.$name)) {
                $created = true;
                $this->setPublicAcl($objectPath.$name);
            }
        } catch (Exception $ex) {
            $this->addError('name',$ex->getMessage());
            return false;
        }

        if (!$created) {
            $this->addError('name',$this->xpdo->lexicon('file_err_create').'<br>'.$objectPath.$name);
            return false;
        }

        $this->xpdo->logManagerAction('file_create','',$objectPath.$name);
        return true;
    }

    /**
     * Update the contents of a specific object
     *
     * @param string $objectPath
     * @param string $content
     * @return boolean
     */
    public function updateObject($objectPath,$content) {
        $updated = false;
        try {
            $temp = tempnam(sys_get_temp_dir(), 'gcs');
            $handle = fopen($temp, "r+b");
            fwrite($handle, $content);
            fseek($handle, 0);
            fclose($handle);
            if ($this->uploadFile($temp, $objectPath)) {
                $updated = true;
                $this->setPublicAcl($objectPath);
            }
        } catch (Exception $ex) {
            $this->addError('name',$ex->getMessage());
            return false;
        }
        
        if (!$updated) {
            $this->addError('name',$this->xpdo->lexicon('file_err_update').$objectPath);
            return false;
        }

        $this->xpdo->logManagerAction('file_update','',$objectPath);
        return true;
    }

    /**
     * Remove an object
     *
     * @param string $objectPath
     * @return boolean
     */
    public function removeObject($objectPath) {
        $deleted = false;
        try {
            if (!$this->driver->objects->get($this->bucket,$objectPath)) {
                $this->xpdo->error->message = $this->xpdo->lexicon('file_folder_err_ns').': '.$objectPath;
                return false;
            }
        } catch (Exception $ex) {}
        try {
            /* remove file from GCS */
            $deleted = $this->driver->objects->delete($this->bucket,$objectPath);
        } catch (Exception $ex) {
            $this->addError('file',$ex->getMessage());
            return false;
        }
        /* log manager action */
        $this->xpdo->logManagerAction('file_remove','',$objectPath);

        return empty($deleted);
    }

    /**
     * Rename a file/object
     *
     * @param string $oldPath
     * @param string $newName
     * @return bool
     */
    public function renameObject($oldPath,$newName) {
        /* check to see if file already exists */
        try {
            if (!$this->driver->objects->get($this->bucket,$oldPath)) {
                $this->addError('file',$this->xpdo->lexicon('file_folder_err_ns').': '.$oldPath);
                return false;
            }
        } catch (Exception $ex) {}
        try {
            $dir = dirname($oldPath);
            $newPath = ($dir != '.' ? $dir.'/' : '').$newName;

            $postBody = new Google_Service_Storage_StorageObject();
            $copied = $this->driver->objects->copy($this->bucket,$oldPath,$this->bucket,$newPath,$postBody,array(
                'projection' => 'full'
            ));
            if (!$copied) {
                $this->addError('file',$this->xpdo->lexicon('file_folder_err_rename').': '.$oldPath);
                return false;
            }
            $this->setPublicAcl($newPath);
            $this->driver->objects->delete($this->bucket,$oldPath);
        } catch (Exception $ex) {
            $this->addError('file',$ex->getMessage());
            return false;
        }

        $this->xpdo->logManagerAction('file_rename','',$oldPath);
        return true;
    }

    /**
     * Upload objects to a specific container
     *
     * @param string $container
     * @param array $objects
     * @return boolean
     */
    public function uploadObjectsToContainer($container,array $objects = array()) {
        if ($container == '/' || $container == '.') $container = '';

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

            $newPath = $container.$file['name'];
            $uploaded = false;
            try {
                if ($this->uploadFile($file['tmp_name'], $newPath)) {
                    $uploaded = true;
                    $this->setPublicAcl($newPath);
                }
            } catch (Exception $ex) {
                $this->addError('path',$ex->getMessage());
            }
            if (!$uploaded) {
                $this->addError('path',$this->xpdo->lexicon('file_err_upload'));
            }
        }

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerUpload',array(
            'files' => &$objects,
            'directory' => $container,
            'source' => &$this,
        ));

        $this->xpdo->logManagerAction('file_upload','',$container);

        return !$this->hasErrors();
    }

    /**
     * Get the content type of the file based on extension
     * @param string $ext
     * @return string
     */
    protected function getContentType($ext) {
        $contentType = 'application/octet-stream';
        $mimeTypes = array(
            '323' => 'text/h323',
            'acx' => 'application/internet-property-stream',
            'ai' => 'application/postscript',
            'aif' => 'audio/x-aiff',
            'aifc' => 'audio/x-aiff',
            'aiff' => 'audio/x-aiff',
            'asf' => 'video/x-ms-asf',
            'asr' => 'video/x-ms-asf',
            'asx' => 'video/x-ms-asf',
            'au' => 'audio/basic',
            'avi' => 'video/x-msvideo',
            'axs' => 'application/olescript',
            'bas' => 'text/plain',
            'bcpio' => 'application/x-bcpio',
            'bin' => 'application/octet-stream',
            'bmp' => 'image/bmp',
            'c' => 'text/plain',
            'cat' => 'application/vnd.ms-pkiseccat',
            'cdf' => 'application/x-cdf',
            'cer' => 'application/x-x509-ca-cert',
            'class' => 'application/octet-stream',
            'clp' => 'application/x-msclip',
            'cmx' => 'image/x-cmx',
            'cod' => 'image/cis-cod',
            'cpio' => 'application/x-cpio',
            'crd' => 'application/x-mscardfile',
            'crl' => 'application/pkix-crl',
            'crt' => 'application/x-x509-ca-cert',
            'csh' => 'application/x-csh',
            'css' => 'text/css',
            'dcr' => 'application/x-director',
            'der' => 'application/x-x509-ca-cert',
            'dir' => 'application/x-director',
            'dll' => 'application/x-msdownload',
            'dms' => 'application/octet-stream',
            'doc' => 'application/msword',
            'dot' => 'application/msword',
            'dvi' => 'application/x-dvi',
            'dxr' => 'application/x-director',
            'eps' => 'application/postscript',
            'etx' => 'text/x-setext',
            'evy' => 'application/envoy',
            'exe' => 'application/octet-stream',
            'fif' => 'application/fractals',
            'flr' => 'x-world/x-vrml',
            'gif' => 'image/gif',
            'gtar' => 'application/x-gtar',
            'gz' => 'application/x-gzip',
            'h' => 'text/plain',
            'hdf' => 'application/x-hdf',
            'hlp' => 'application/winhlp',
            'hqx' => 'application/mac-binhex40',
            'hta' => 'application/hta',
            'htc' => 'text/x-component',
            'htm' => 'text/html',
            'html' => 'text/html',
            'htt' => 'text/webviewhtml',
            'ico' => 'image/x-icon',
            'ief' => 'image/ief',
            'iii' => 'application/x-iphone',
            'ins' => 'application/x-internet-signup',
            'isp' => 'application/x-internet-signup',
            'jfif' => 'image/pipeg',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'js' => 'application/x-javascript',
            'latex' => 'application/x-latex',
            'lha' => 'application/octet-stream',
            'lsf' => 'video/x-la-asf',
            'lsx' => 'video/x-la-asf',
            'lzh' => 'application/octet-stream',
            'm13' => 'application/x-msmediaview',
            'm14' => 'application/x-msmediaview',
            'm3u' => 'audio/x-mpegurl',
            'man' => 'application/x-troff-man',
            'mdb' => 'application/x-msaccess',
            'me' => 'application/x-troff-me',
            'mht' => 'message/rfc822',
            'mhtml' => 'message/rfc822',
            'mid' => 'audio/mid',
            'mny' => 'application/x-msmoney',
            'mov' => 'video/quicktime',
            'movie' => 'video/x-sgi-movie',
            'mp2' => 'video/mpeg',
            'mp3' => 'audio/mpeg',
            'mpa' => 'video/mpeg',
            'mpe' => 'video/mpeg',
            'mpeg' => 'video/mpeg',
            'mpg' => 'video/mpeg',
            'mpp' => 'application/vnd.ms-project',
            'mpv2' => 'video/mpeg',
            'ms' => 'application/x-troff-ms',
            'mvb' => 'application/x-msmediaview',
            'nws' => 'message/rfc822',
            'oda' => 'application/oda',
            'p10' => 'application/pkcs10',
            'p12' => 'application/x-pkcs12',
            'p7b' => 'application/x-pkcs7-certificates',
            'p7c' => 'application/x-pkcs7-mime',
            'p7m' => 'application/x-pkcs7-mime',
            'p7r' => 'application/x-pkcs7-certreqresp',
            'p7s' => 'application/x-pkcs7-signature',
            'pbm' => 'image/x-portable-bitmap',
            'pdf' => 'application/pdf',
            'pfx' => 'application/x-pkcs12',
            'pgm' => 'image/x-portable-graymap',
            'pko' => 'application/ynd.ms-pkipko',
            'pma' => 'application/x-perfmon',
            'pmc' => 'application/x-perfmon',
            'pml' => 'application/x-perfmon',
            'pmr' => 'application/x-perfmon',
            'pmw' => 'application/x-perfmon',
            'pnm' => 'image/x-portable-anymap',
            'pot' => 'application/vnd.ms-powerpoint',
            'ppm' => 'image/x-portable-pixmap',
            'pps' => 'application/vnd.ms-powerpoint',
            'ppt' => 'application/vnd.ms-powerpoint',
            'prf' => 'application/pics-rules',
            'ps' => 'application/postscript',
            'pub' => 'application/x-mspublisher',
            'qt' => 'video/quicktime',
            'ra' => 'audio/x-pn-realaudio',
            'ram' => 'audio/x-pn-realaudio',
            'ras' => 'image/x-cmu-raster',
            'rgb' => 'image/x-rgb',
            'rmi' => 'audio/mid',
            'roff' => 'application/x-troff',
            'rtf' => 'application/rtf',
            'rtx' => 'text/richtext',
            'scd' => 'application/x-msschedule',
            'sct' => 'text/scriptlet',
            'setpay' => 'application/set-payment-initiation',
            'setreg' => 'application/set-registration-initiation',
            'sh' => 'application/x-sh',
            'shar' => 'application/x-shar',
            'sit' => 'application/x-stuffit',
            'snd' => 'audio/basic',
            'spc' => 'application/x-pkcs7-certificates',
            'spl' => 'application/futuresplash',
            'src' => 'application/x-wais-source',
            'sst' => 'application/vnd.ms-pkicertstore',
            'stl' => 'application/vnd.ms-pkistl',
            'stm' => 'text/html',
            'svg' => 'image/svg+xml',
            'sv4cpio' => 'application/x-sv4cpio',
            'sv4crc' => 'application/x-sv4crc',
            't' => 'application/x-troff',
            'tar' => 'application/x-tar',
            'tcl' => 'application/x-tcl',
            'tex' => 'application/x-tex',
            'texi' => 'application/x-texinfo',
            'texinfo' => 'application/x-texinfo',
            'tgz' => 'application/x-compressed',
            'tif' => 'image/tiff',
            'tiff' => 'image/tiff',
            'tr' => 'application/x-troff',
            'trm' => 'application/x-msterminal',
            'tsv' => 'text/tab-separated-values',
            'txt' => 'text/plain',
            'uls' => 'text/iuls',
            'ustar' => 'application/x-ustar',
            'vcf' => 'text/x-vcard',
            'vrml' => 'x-world/x-vrml',
            'wav' => 'audio/x-wav',
            'wcm' => 'application/vnd.ms-works',
            'wdb' => 'application/vnd.ms-works',
            'wks' => 'application/vnd.ms-works',
            'wmf' => 'application/x-msmetafile',
            'wps' => 'application/vnd.ms-works',
            'wri' => 'application/x-mswrite',
            'wrl' => 'x-world/x-vrml',
            'wrz' => 'x-world/x-vrml',
            'xaf' => 'x-world/x-vrml',
            'xbm' => 'image/x-xbitmap',
            'xla' => 'application/vnd.ms-excel',
            'xlc' => 'application/vnd.ms-excel',
            'xlm' => 'application/vnd.ms-excel',
            'xls' => 'application/vnd.ms-excel',
            'xlt' => 'application/vnd.ms-excel',
            'xlw' => 'application/vnd.ms-excel',
            'xof' => 'x-world/x-vrml',
            'xpm' => 'image/x-xpixmap',
            'xwd' => 'image/x-xwindowdump',
            'z' => 'application/x-compress',
            'zip' => 'application/zip'
        );
        if (isset($mimeTypes[strtolower($ext)])) {
            $contentType = $mimeTypes[strtolower($ext)];
        } else {
            $contentType = 'octet/application-stream';
        }
        return $contentType;
    }

    /**
     * Move a file or folder to a specific location
     *
     * @param string $from The location to move from
     * @param string $to The location to move to
     * @param string $point The type of move; append, above, below
     * @return boolean
     */
    public function moveObject($from,$to,$point = 'append') {
        $this->xpdo->lexicon->load('source');
        $success = false;

        if (substr(strrev($from),0,1) == '/') {
            $this->xpdo->error->message = $this->xpdo->lexicon('gcs_no_move_folder',array(
                'from' => $from
            ));
            return $success;
        }

        try {
            if (!$this->driver->objects->get($this->bucket,$from)) {
                $this->xpdo->error->message = $this->xpdo->lexicon('file_err_ns').': '.$from;
                return $success;
            }

            if ($to != '/') {
                if (!$this->driver->objects->get($this->bucket,$to)) {
                    $this->xpdo->error->message = $this->xpdo->lexicon('file_err_ns').': '.$to;
                    return $success;
                }
                $toPath = rtrim($to,'/').'/'.basename($from);
            } else {
                $toPath = basename($from);
            }

            $postBody = new Google_Service_Storage_StorageObject();
            $response = $this->driver->objects->copy($this->bucket,$from,$this->bucket,$toPath,$postBody,array('projection' => 'full'));
            $this->setPublicAcl($toPath);
            if ($response) {
                $this->driver->objects->delete($this->bucket,$from);
                $success = true;
            } else {
                $this->xpdo->error->message = $this->xpdo->lexicon('file_folder_err_rename').': '.$to.' -> '.$from;
            }
        } catch (Exception $ex) {
            $this->xpdo->error->message = $ex->getMessage();
            return $success;
        }

        return $success;
    }

    /**
     * Get the default properties for this source. Override this in your custom source driver to provide custom
     * properties for your source type.
     * @return array
     */
    public function getDefaultProperties() {
        return array(
            'gcs.app_name' => array(
                'name' => 'gcs.app_name',
                'desc' => 'prop_gcs.app_name_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ),
            'gcs.client_id' => array(
                'name' => 'gcs.client_id',
                'desc' => 'prop_gcs.client_id_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ),
            'gcs.email_address' => array(
                'name' => 'gcs.email_address',
                'desc' => 'prop_gcs.email_address_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ),
            'gcs.private_key_file' => array(
                'name' => 'gcs.private_key_file',
                'desc' => 'prop_gcs.private_key_file_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ),
            'gcs.default_bucket_name' => array(
                'name' => 'gcs.default_bucket_name',
                'desc' => 'prop_gcs.default_bucket_name_desc',
                'type' => 'textfield',
                'value' => '',
                'lexicon' => 'core:source',
            ),
            'gcs.scopes' => array(
                'name' => 'gcs.scopes',
                'desc' => 'prop_gcs.scopes_desc',
                'type' => 'textfield',
                'value' => 'https://www.googleapis.com/auth/devstorage.full_control',
                'lexicon' => 'core:source',
            ),
            'imageExtensions' => array(
                'name' => 'imageExtensions',
                'desc' => 'prop_s3.imageExtensions_desc',
                'type' => 'textfield',
                'value' => 'jpg,jpeg,png,gif',
                'lexicon' => 'core:source',
            ),
            'thumbnailType' => array(
                'name' => 'thumbnailType',
                'desc' => 'prop_s3.thumbnailType_desc',
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
                'desc' => 'prop_s3.skipFiles_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '.svn,.git,_notes,nbproject,.idea,.DS_Store',
                'lexicon' => 'core:source',
            ),
        );
    }

    /**
     * Prepare a src parameter to be rendered with phpThumb
     * 
     * @param string $src
     * @return string
     */
    public function prepareSrcForThumb($src) {
        $url = 'https://'.$this->bucket . '.storage.googleapis.com/';
        if (strpos($src,$url) === false) {
            $src = $url.ltrim($src,'/');
        }
        return $src;
    }

    /**
     * Get the base URL for this source. Only applicable to sources that are streams; used for determining the base
     * URL with Static objects and downloading objects.
     * 
     * @param string $object $object An optional object to find the base url of
     * @return void
     */
    public function getBaseUrl($object = '') {
        return 'https://'.$this->bucket . '.storage.googleapis.com/';
    }

    /**
     * Get the URL for an object in this source. Only applicable to sources that are streams; used for determining
     * the base URL with Static objects and downloading objects.
     *
     * @param string $object
     * @return void
     */
    public function getObjectUrl($object = '') {
        return 'https://'.$this->bucket . '.storage.googleapis.com/'.$object;
    }

    public function getObjectFileSize($filename) {
        try {
            return $this->driver->objects->get($this->bucket, $filename)->getSize();
        } catch (Exception $ex) {
            $this->xpdo->error->message = $ex->getMessage();
            return 0;
        }
    }

    /**
     * Tells if a file is a binary file or not.
     *
     * @param string $file
     * @return boolean True if a binary file.
     */
    public function isBinary($file, $isContent = false) {
        if(!$isContent) {
            $file = file_get_contents($file, null, null, null, 512);
        }

        $content = str_replace(array("\n", "\r", "\t"), '', $file);
        return ctype_print($content) ? false : true;
    }

    /**
     * Get the contents of an object
     *
     * @param string $objectPath
     * @return boolean
     */
    public function getObjectContents($objectPath) {
        $url = 'https://'.$this->bucket . '.storage.googleapis.com/';
        $objectUrl = $url.$objectPath;
        $contents = @file_get_contents($objectUrl);

        $imageExtensions = $this->getOption('imageExtensions',$this->properties,'jpg,jpeg,png,gif');
        $imageExtensions = explode(',',$imageExtensions);
        $fileExtension = pathinfo($objectPath,PATHINFO_EXTENSION);

        return array(
            'name' => $objectPath,
            'basename' => basename($objectPath),
            'path' => $objectPath,
            'size' => $this->getObjectFileSize($objectPath),
            'last_accessed' => '',
            'last_modified' => '',
            'content' => $contents,
            'image' => in_array($fileExtension,$imageExtensions) ? true : false,
            'is_writable' => !$this->isBinary($contents, true),
            'is_readable' => true,
        );
    }

    /**
     * Set GCS's public access permission to the given path
     * @param string $path
     * @return object Google_Service_Storage_ObjectAccessControl
     */
    protected function setPublicAcl($path) {
        $acl = new Google_Service_Storage_ObjectAccessControl();
        $acl->setEntity('allUsers');
        $acl->setRole('READER');
        return $this->driver->objectAccessControls->insert($this->bucket, $path, $acl);
    }

    /**
     * Upload object
     * @param string $filePath absolute path to the phyisical file
     * @param string $fileName full name in GCS
     * @param boolean $deleteSource should delete the source file?
     * @return mixed
     */
    protected function uploadFile($filePath, $fileName, $deleteSource = true) {
        $handle = fopen($filePath, "rb");
        fseek($handle, 0);
        $postBody = new Google_Service_Storage_StorageObject();
        $postBody->setName($fileName);
        $postBody->setUpdated(date("c"));
        $postBody->setGeneration(time());
        $size = @filesize($filePath);
        $postBody->setSize($size);
        $ext = @pathinfo($fileName,PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        $mimeType = $this->getContentType($ext);
        $postBody->setContentType($mimeType);
        $chunkSizeBytes = 1 * 1024 * 1024;
        $this->client->setDefer(true);
        $request = $this->driver->objects->insert($this->bucket,$postBody,array(
            'name' => $fileName,
            'projection' => 'full',
            'uploadType' => 'resumable',
        ));
        $media = new Google_Http_MediaFileUpload(
                $this->client,
                $request,
                $mimeType,
                null,
                true,
                $chunkSizeBytes
        );
        $media->setFileSize($size);
        $status = false;
        while (!$status && !feof($handle)) {
            $chunk = fread($handle, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }
        fclose($handle);
        $this->client->setDefer(false);
        if ($status && $deleteSource) {
            unlink($filePath);
        }
        return $status;
    }
    
}
