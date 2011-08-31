<?php
/**
 * @package modx
 * @subpackage registry.db.mysql
 */
/**
 * @package modx
 * @subpackage sources
 */
class modS3MediaSource extends modMediaSource {
    /** @var AmazonS3 $driver */
    public $driver;
    /** @var string $bucket */
    public $bucket;

    /**
     * Initializes S3 media class, getting the S3 driver and loading the bucket
     * @return void
     */
    public function initialize() {
        parent::initialize();
        if (!defined('AWS_KEY')) {
            define('AWS_KEY',$this->xpdo->getOption('aws.key',null,''));
            define('AWS_SECRET_KEY',$this->xpdo->getOption('aws.secret_key',null,''));
            /* (Not needed at this time)
            define('AWS_ACCOUNT_ID',$modx->getOption('aws.account_id',$config,''));
            define('AWS_CANONICAL_ID',$modx->getOption('aws.canonical_id',$config,''));
            define('AWS_CANONICAL_NAME',$modx->getOption('aws.canonical_name',$config,''));
            define('AWS_MFA_SERIAL',$modx->getOption('aws.mfa_serial',$config,''));
            define('AWS_CLOUDFRONT_KEYPAIR_ID',$modx->getOption('aws.cloudfront_keypair_id',$config,''));
            define('AWS_CLOUDFRONT_PRIVATE_KEY_PEM',$modx->getOption('aws.cloudfront_private_key_pem',$config,''));
            define('AWS_ENABLE_EXTENSIONS', 'false');*/
        }
        include $this->xpdo->getOption('core_path',null,MODX_CORE_PATH).'model/aws/sdk.class.php';

        $this->getDriver();
        $this->setBucket($this->xpdo->getOption('aws.default_bucket',$config,''));
    }

    /**
     * Get the name of this source type
     * @return string
     */
    public function getTypeName() {
        $this->xpdo->lexicon->load('source');
        return $this->xpdo->lexicon('source_type.s3');
    }
    /**
     * Get the description of this source type
     * @return string
     */
    public function getTypeDescription() {
        $this->xpdo->lexicon->load('source');
        return $this->xpdo->lexicon('source_type.s3_desc');
    }


    /**
     * Gets the AmazonS3 class instance
     * @return AmazonS3
     */
    public function getDriver() {
        if (empty($this->driver)) {
            try {
                $this->driver = new AmazonS3();
            } catch (Exception $e) {
                $this->xpdo->log(modX::LOG_LEVEL_ERROR,'[modAws] Could not load AmazonS3 class: '.$e->getMessage());
            }
        }
        return $this->driver;
    }

    /**
     * Set the bucket for the connection to S3
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
    public function getObjectList($dir) {
        $c['delimiter'] = '/';
        if (!empty($dir) && $dir != '/') { $c['prefix'] = $dir; }

        $list = array();
        $cps = $this->driver->list_objects($this->bucket,$c);
        foreach ($cps->body->CommonPrefixes as $prefix) {
            if (!empty($prefix->Prefix) && $prefix->Prefix != $dir && $prefix->Prefix != '/') {
                $list[] = (string)$prefix->Prefix;
            }
        }
        $response = $this->driver->get_object_list($this->bucket,$c);
        foreach ($response as $file) {
            $list[] = $file;
        }
        return $list;
    }

    /**
     * @param string $dir
     * @return array
     */
    public function getFolderList($dir) {
        $properties = $this->getProperties();
        $list = $this->getObjectList($dir);

        $imagesExts = array('jpg','jpeg','png','gif');
        $use_multibyte = $this->ctx->getOption('use_multibyte', false);
        $encoding = $this->ctx->getOption('modx_charset', 'UTF-8');

        $directories = array();
        $files = array();
        foreach ($list as $idx => $path) {
            if ($path == $dir) continue;
            $fileName = basename($path);
            $isDir = substr(strrev($path),0,1) === '/';

            $extension = pathinfo($fileName,PATHINFO_EXTENSION);
            $extension = $use_multibyte ? mb_strtolower($extension,$encoding) : strtolower($extension);

            $relativePath = $path == '/' ? $path : str_replace($dir,'',$path);
            $slashCount = substr_count($relativePath,'/');
            if (($slashCount > 1 && $isDir) || ($slashCount > 0 && !$isDir)) {
                continue;
            }
            if ($isDir) {
                $directories[$path] = array(
                    'id' => $path,
                    'text' => $fileName,
                    'cls' => 'icon-'.$extension,
                    'type' => 'dir',
                    'leaf' => false,
                    'path' => $path,
                    'pathRelative' => $path,
                    'perms' => '',
                );
                $directories[$path]['menu'] = array('items' => $this->getListContextMenu($path,$isDir,$directories[$path]));
            } else {
                $files[$path] = array(
                    'id' => $path,
                    'text' => $fileName,
                    'cls' => 'icon-'.$extension,
                    'type' => 'file',
                    'leaf' => true,
                    //'qtip' => in_array($ext,$imagesExts) ? '<img src="'.$fromManagerUrl.'" alt="'.$fileName.'" />' : '',
                    'path' => $path,
                    'pathRelative' => $path,
                    'directory' => $path,
                    'url' => $properties['url']['value'].$properties['bucket']['value'].'/'.$path,
                    'file' => $path,
                );
                $files[$path]['menu'] = array('items' => $this->getListContextMenu($path,$isDir,$files[$path]));
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

    public function getListContextMenu($file,$isDir,array $fileArray) {
        $menu = array();
        if (!$isDir) { /* files */
            if ($this->hasPermission('file_update')) {
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

    public function getFilesInDirectory($dir) {
        $list = $this->getObjectList($dir);
        $properties = $this->getProperties();

        $modAuth = $_SESSION["modx.{$this->xpdo->context->get('key')}.user.token"];

        /* get default settings */
        $imagesExts = array('jpg','jpeg','png','gif');
        $use_multibyte = $this->ctx->getOption('use_multibyte', false);
        $encoding = $this->ctx->getOption('modx_charset', 'UTF-8');
        $allowedFileTypes = $this->getOption('allowedFileTypes',$this->properties,'');
        $allowedFileTypes = !empty($allowedFileTypes) && is_string($allowedFileTypes) ? explode(',',$allowedFileTypes) : $allowedFileTypes;
        $bucketUrl = $properties['url']['value'].'/';

        /* iterate */
        $files = array();
        foreach ($list as $object) {
            $objectUrl = $bucketUrl.$object;
            $baseName = basename($object);
            $isDir = substr(strrev($object),0,1) == '/' ? true : false;
            if (in_array($object,array('.','..','.svn','.git','_notes','.DS_Store'))) continue;

            if (!$isDir) {
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
                if (in_array($fileArray['ext'],$imagesExts)) {
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
                        'f' => 'png',
                        'q' => 90,
                        'HTTP_MODAUTH' => $modAuth,
                        'wctx' => $this->ctx->get('key'),
                        'source' => $this->get('id'),
                    ));
                    $imageQuery = http_build_query(array(
                        'src' => $object,
                        'w' => $imageWidth,
                        'h' => $imageHeight,
                        'HTTP_MODAUTH' => $modAuth,
                        'f' => 'png',
                        'q' => 90,
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
        return $files;
    }

    /**
     * Create a folder
     *
     * @param string $name
     * @param string $parentFolder
     * @return boolean
     */
    public function createFolder($name,$parentFolder) {
        $newPath = $parentFolder.rtrim($name,'/').'/';
        /* check to see if folder already exists */
        if ($this->driver->if_object_exists($this->bucket,$newPath)) {
            $this->addError('file',$this->xpdo->lexicon('file_folder_err_ae').': '.$newPath);
            return false;
        }

        /* create empty file that acts as folder */
        $created = $this->driver->create_object($this->bucket,$newPath,array(
            'body' => '',
            'acl' => AmazonS3::ACL_PUBLIC,
            'length' => 0,
        ));

        if (!$created) {
            $this->addError('name',$this->xpdo->lexicon('file_folder_err_create').$newPath);
            return false;
        }

        $this->xpdo->logManagerAction('directory_create','',$newPath);
        return true;
    }

    /**
     * Remove an empty folder from s3
     *
     * @param $filePath
     * @return boolean
     */
    public function removeFolder($filePath) {
        if (!$this->driver->if_object_exists($this->bucket,$filePath)) {
            $this->addError('file',$this->xpdo->lexicon('file_folder_err_ns').': '.$filePath);
            return false;
        }

        /* remove file from s3 */
        $deleted = $this->driver->delete_object($this->bucket,$filePath);

        /* log manager action */
        $this->xpdo->logManagerAction('directory_remove','',$filePath);

        return !empty($deleted);
    }


    /**
     * Delete a file from S3
     * 
     * @param string $filePath
     * @return boolean
     */
    public function removeFile($filePath) {
        if (!$this->driver->if_object_exists($this->bucket,$filePath)) {
            $this->addError('file',$this->xpdo->lexicon('file_folder_err_ns').': '.$filePath);
            return false;
        }

        /* remove file from s3 */
        $deleted = $this->driver->delete_object($this->bucket,$filePath);

        /* log manager action */
        $this->xpdo->logManagerAction('file_remove','',$filePath);

        return !empty($deleted);
    }

    /**
     * @param string $oldPath
     * @param string $newName
     * @return bool
     */
    public function renameFile($oldPath,$newName) {
        if (!$this->driver->if_object_exists($this->bucket,$oldPath)) {
            $this->addError('file',$this->xpdo->lexicon('file_folder_err_ns').': '.$oldPath);
            return false;
        }
        $dir = dirname($oldPath);
        $newPath = ($dir != '.' ? $dir.'/' : '').$newName;

        $copied = $this->driver->copy_object(array(
            'bucket' => $this->bucket,
            'filename' => $oldPath,
        ),array(
            'bucket' => $this->bucket,
            'filename' => $newPath,
        ),array(
            'acl' => AmazonS3::ACL_PUBLIC,
        ));
        if (!$copied) {
            $this->addError('file',$this->xpdo->lexicon('file_folder_err_rename').': '.$oldPath);
            return false;
        }

        $this->driver->delete_object($this->bucket,$oldPath);

        $this->xpdo->logManagerAction('file_rename','',$oldPath);
        return true;
    }


    public function uploadToFolder($targetDirectory,$files) {
        if ($targetDirectory == '/' || $targetDirectory == '.') $targetDirectory = '';

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

            $newPath = $targetDirectory.$file['name'];

            $uploaded = $this->driver->create_object($this->bucket,$newPath,array(
                'fileUpload' => $file['tmp_name'],
                'acl' => AmazonS3::ACL_PUBLIC,
                'length' => $size,
            ));

            if (!$uploaded) {
                $this->addError('path',$this->xpdo->lexicon('file_err_upload'));
            }
        }

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerUpload',array(
            'files' => &$_FILES,
            'directory' => &$targetDirectory,
            'source' => &$this,
        ));

        $this->xpdo->logManagerAction('file_upload','',$targetDirectory);

        return true;
    }

    /**
     * @return array
     */
    public function getDefaultProperties() {
        return array(
            'url' => array(
                'name' => 'url',
                'desc' => 'prop_s3.url_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => 'http://mysite.s3.amazonaws.com/',
                'lexicon' => 'core:source',
            ),
            'bucket' => array(
                'name' => 'bucket',
                'desc' => 'prop_s3.bucket_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ),
        );
    }


    public function prepareSrcForThumb($src) {
        $properties = $this->getProperties();
        $src = $properties['url']['value'].$src;
        return $src;
    }
}