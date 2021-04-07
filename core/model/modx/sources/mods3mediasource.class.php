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
 * Implements an Amazon S3-based media source, allowing basic manipulation, uploading and URL-retrieval of resources
 * in a specified S3 bucket.
 *
 * @package modx
 * @subpackage sources
 */
class modS3MediaSource extends modMediaSource implements modMediaSourceInterface {
    /** @var AmazonS3 $driver */
    public $driver;
    /** @var string $bucket */
    public $bucket;

    /**
     * Override the constructor to always force S3 sources to not be streams.
     *
     * {@inheritDoc}
     *
     * @param xPDO $xpdo
     */
    public function __construct(xPDO & $xpdo) {
        parent::__construct($xpdo);
        $this->set('is_stream',false);
    }

    /**
     * Initializes S3 media class, getting the S3 driver and loading the bucket
     * @return boolean
     */
    public function initialize() {
        $return = parent::initialize();
        $properties = $this->getPropertyList();
        if (!defined('AWS_KEY')) {
            define('AWS_KEY',$this->xpdo->getOption('key',$properties,''));
            define('AWS_SECRET_KEY',$this->xpdo->getOption('secret_key',$properties,''));
            /* (Not needed at this time)
            define('AWS_ACCOUNT_ID',$modx->getOption('aws.account_id',$config,''));
            define('AWS_CANONICAL_ID',$modx->getOption('aws.canonical_id',$config,''));
            define('AWS_CANONICAL_NAME',$modx->getOption('aws.canonical_name',$config,''));
            define('AWS_MFA_SERIAL',$modx->getOption('aws.mfa_serial',$config,''));
            define('AWS_CLOUDFRONT_KEYPAIR_ID',$modx->getOption('aws.cloudfront_keypair_id',$config,''));
            define('AWS_CLOUDFRONT_PRIVATE_KEY_PEM',$modx->getOption('aws.cloudfront_private_key_pem',$config,''));
            define('AWS_ENABLE_EXTENSIONS', 'false');*/
        }
        include_once $this->xpdo->getOption('core_path',null,MODX_CORE_PATH).'model/aws/sdk.class.php';

        $this->getDriver();

        $region = $this->xpdo->getOption('region',$properties,'');
        if (!empty($region)) {
            $this->driver->set_region($region);
        }

        $this->setBucket($this->xpdo->getOption('bucket',$properties,''));

        return $return;
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
        if (strpos($bucket,'.') !== false) {
            $this->driver->enable_path_style(true);
        }
    }

    /**
     * Get a list of objects from within a bucket
     * @param string $dir
     * @return array
     */
    public function getS3ObjectList($dir) {
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
     * Get the ID of the edit file action
     *
     * @return boolean|int
     */
    public function getEditActionId() {
        return 'system/file/edit';
    }

    /**
     * @param string $path
     * @return array
     */
    public function getContainerList($path) {
        $properties = $this->getPropertyList();
        $list = $this->getS3ObjectList($path);
        $editAction = $this->getEditActionId();

        $useMultiByte = $this->ctx->getOption('use_multibyte', false);
        $encoding = $this->ctx->getOption('modx_charset', 'UTF-8');

        $imagesExts = $this->getOption('imageExtensions',$properties,'jpg,jpeg,png,gif,svg');
        $imagesExts = explode(',',$imagesExts);

        $hideTooltips = !empty($properties['hideTooltips']) && $properties['hideTooltips'] != 'false' ? true : false;

        $directories = array();
        $dirnames = array();
        $files = array();
        $filenames = array();

        foreach ($list as $idx => $currentPath) {
            if ($currentPath == $path) continue;
            $fileName = basename($currentPath);
            $isDir = substr(strrev($currentPath),0,1) === '/';

            $ext = pathinfo($fileName,PATHINFO_EXTENSION);
            $ext = $useMultiByte ? mb_strtolower($ext,$encoding) : strtolower($ext);

            $relativePath = $currentPath == '/' ? $currentPath : str_replace($path,'',$currentPath);
            $slashCount = substr_count($relativePath,'/');
            if (($slashCount > 1 && $isDir) || ($slashCount > 0 && !$isDir)) {
                continue;
            }

            $cls = array();
            if ($isDir) {
                $cls[] = 'folder';
                $dirnames[] = strtoupper($fileName);
                $directories[$currentPath] = array(
                    'id' => $currentPath,
                    'text' => $fileName,
                    'cls' => implode(' ',$cls),
                    'iconCls' => 'icon icon-folder',
                    'type' => 'dir',
                    'leaf' => false,
                    'path' => $currentPath,
                    'pathRelative' => $currentPath,
                    'perms' => '',
                );
                $directories[$currentPath]['menu'] = array('items' => $this->getListContextMenu($currentPath,$isDir,$directories[$currentPath]));
            } else {
                $url = rtrim($properties['url'],'/').'/'.$currentPath;
                $url = str_replace(' ','%20',$url);
                $page = '?a='.$editAction.'&file='.rawurlencode($currentPath).'&wctx='.$this->ctx->get('key').'&source='.$this->get('id');
                // $isBinary = $this->isBinary(rtrim($properties['url'],'/').'/'.$currentPath);

                // $cls = array();
                // $cls[] = 'icon-'.$ext;
                // if($isBinary) {
                //     $cls[] = 'icon-lock';
                // }

                if ($this->hasPermission('file_remove')) $cls[] = 'premove';
                if ($this->hasPermission('file_update')) $cls[] = 'pupdate';

                $filenames[] = strtoupper($fileName);
                $files[$currentPath] = array(
                    'id' => $currentPath,
                    'text' => $fileName,
                    'cls' => implode(' ', $cls),
                    'iconCls' => 'icon icon-file icon-'.$ext,
                    'type' => 'file',
                    'leaf' => true,
                    'path' => $currentPath,
                    'page' => $this->isBinary($url) ? $page : null,
                    'pathRelative' => $currentPath,
                    'directory' => $currentPath,
                    'url' => $url,
                    'file' => $currentPath,
                );
                $files[$currentPath]['menu'] = array('items' => $this->getListContextMenu($currentPath,$isDir,$files[$currentPath]));

                if (!$hideTooltips) {

                    $files[$currentPath]['qtip'] = '';

                    if (in_array($ext, $imagesExts)) {

                        $modAuth = $this->xpdo->user->getUserToken($this->xpdo->context->get('key'));

                        $preview = true;
                        $imageWidth = $this->ctx->getOption('filemanager_image_width', 400);
                        $imageHeight = $this->ctx->getOption('filemanager_image_height', 300);
                        $thumbnailType = $this->getOption('thumbnailType', $properties, 'png');
                        $thumbnailQuality = $this->getOption('thumbnailQuality', $properties, 90);

                        if ($ext == 'svg') {
                            $svgString = @file_get_contents($bases['pathAbsoluteWithPath'].$url);
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
                            $size = @getimagesize($url);
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
                                    'src' => $url,
                                    'w' => $imageQueryWidth,
                                    'h' => $imageQueryHeight,
                                    'HTTP_MODAUTH' => $modAuth,
                                    'f' => $thumbnailType,
                                    'q' => $thumbnailQuality,
                                    'wctx' => $this->ctx->get('key'),
                                    'source' => $this->get('id'),
                                ));
                                $image = $this->ctx->getOption('connectors_url', MODX_CONNECTORS_URL).'system/phpthumb.php?'.urldecode($imageQuery);
                            } else {
                                $preview = false;
                                $this->xpdo->log(modX::LOG_LEVEL_ERROR,'Thumbnail could not be created for file: '.$url);
                            }
                        }

                        if ($preview) {
                            $files[$currentPath]['qtip'] = '<img src="'.$image.'" width="'.$imageWidth.'" height="'.$imageHeight.'" alt="'.$fileName.'" />';
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
     * Get all files in the directory and prepare thumbnail views
     *
     * @param string $path
     * @return array
     */
    public function getObjectsInContainer($path) {
        $properties = $this->getPropertyList();
        $list = $this->getS3ObjectList($path);
        $editAction = $this->getEditActionId();

        $modAuth = $this->xpdo->user->getUserToken($this->xpdo->context->get('key'));

        /* get default settings */
        $use_multibyte = $this->ctx->getOption('use_multibyte', false);
        $encoding = $this->ctx->getOption('modx_charset', 'UTF-8');
        $bucketUrl = rtrim($properties['url'],'/').'/';
        $allowedFileTypes = $this->getOption('allowedFileTypes',$this->properties,'');
        $allowedFileTypes = !empty($allowedFileTypes) && is_string($allowedFileTypes) ? array_map("trim",explode(',',$allowedFileTypes)) : $allowedFileTypes;
        $imageExtensions = $this->getOption('imageExtensions',$this->properties,'jpg,jpeg,png,gif,svg');
        $imageExtensions = explode(',',$imageExtensions);
        $thumbnailType = $this->getOption('thumbnailType',$this->properties,'png');
        $thumbnailQuality = $this->getOption('thumbnailQuality',$this->properties,90);
        $skipFiles = $this->getOption('skipFiles',$this->properties,'.svn,.git,_notes,nbproject,.idea,.DS_Store');
        $skipFiles = array_map("trim",explode(',',$skipFiles));
        $skipFiles[] = '.';
        $skipFiles[] = '..';

        /* iterate */
        $files = array();
        $filenames = array();

        foreach ($list as $idx => $currentPath) {
            $url = $bucketUrl.trim($currentPath,'/');
            $url = str_replace(' ','%20',$url);
            $fileName = basename($currentPath);
            $isDir = substr(strrev($currentPath),0,1) == '/' ? true : false;
            if (in_array($currentPath,$skipFiles)) continue;

            if (!$isDir) {
                $page = '?a='.$editAction.'&file='.$currentPath.'&wctx='.$this->ctx->get('key').'&source='.$this->get('id');
                // $isBinary = $this->isBinary(rtrim($properties['url'],'/').'/'.$currentPath);

                // get filesize from S3
                $filesize = 0;
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_NOBODY, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                if (curl_exec($ch) !== false) {
                    $filesize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
                }
                curl_close($ch);

                $filenames[] = strtoupper($fileName);
                $fileArray = array(
                    'id' => $currentPath,
                    'name' => $fileName,
                    'url' => $url,
                    'relativeUrl' => $url,
                    'fullRelativeUrl' => $url,
                    'pathname' => $url,
                    'pathRelative' => $currentPath,
                    'size' => $filesize,
                    'page' => $this->isBinary($url) ? $page : null,
                    'leaf' => true,
                    // 'menu' => array(
                    //     array('text' => $this->xpdo->lexicon('file_remove'),'handler' => 'this.removeFile'),
                    // ),
                );

                $fileArray['ext'] = pathinfo($fileName,PATHINFO_EXTENSION);
                $fileArray['ext'] = $use_multibyte ? mb_strtolower($fileArray['ext'],$encoding) : strtolower($fileArray['ext']);
                $fileArray['cls'] = 'icon-'.$fileArray['ext'];

                if (!empty($allowedFileTypes) && !in_array($fileArray['ext'],$allowedFileTypes)) continue;

                /* get thumbnail */
                $preview = 0;

                if (in_array($fileArray['ext'],$imageExtensions)) {
                    $preview = 1;
                    $imageWidth = $this->ctx->getOption('filemanager_image_width', 800);
                    $imageHeight = $this->ctx->getOption('filemanager_image_height', 600);
                    $thumbWidth = $this->ctx->getOption('filemanager_thumb_width', 100);
                    $thumbHeight = $this->ctx->getOption('filemanager_thumb_height', 80);

                    $size = array($imageWidth, $imageHeight);

                    if ($fileArray['ext'] == 'svg') {
                        $svgString = @file_get_contents($url);
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
                        $fileArray['image'] = $fileArray['thumb'] = $url;
                    } else {
                        $size = @getimagesize($url);
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
                            ));
                            $fileArray['image'] = $this->ctx->getOption('connectors_url', MODX_CONNECTORS_URL).'system/phpthumb.php?'.urldecode($imageQuery);
                            $thumbQuery = http_build_query(array(
                                'src' => $url,
                                'w' => $thumbQueryWidth,
                                'h' => $thumbQueryHeight,
                                'HTTP_MODAUTH' => $modAuth,
                                'f' => $thumbnailType,
                                'q' => $thumbnailQuality,
                                'wctx' => $this->ctx->get('key'),
                                'source' => $this->get('id'),
                            ));
                            $fileArray['thumb'] = $this->ctx->getOption('connectors_url', MODX_CONNECTORS_URL).'system/phpthumb.php?'.urldecode($thumbQuery);
                        } else {
                            $this->xpdo->log(modX::LOG_LEVEL_ERROR,'Thumbnail could not be created for file: '.$url);
                            $preview = 0;
                        }
                    }
                    if ($preview) {
                        $fileArray['thumb_width'] = $thumbWidth;
                        $fileArray['thumb_height'] = $thumbHeight;
                        $fileArray['image_width'] = is_array($size) && $size[0] > 0 ? $size[0] : $imageWidth;
                        $fileArray['image_height'] = is_array($size) && $size[1] > 0 ? $size[1] : $imageHeight;
                    }

                }
                if ($preview == 0) {
                    $fileArray['thumb'] = $fileArray['image'] = $this->ctx->getOption('manager_url', MODX_MANAGER_URL).'templates/default/images/restyle/nopreview.jpg';
                    $fileArray['thumb_width'] = $fileArray['image_width'] = $this->ctx->getOption('filemanager_thumb_width', 100);
                    $fileArray['thumb_height'] = $fileArray['image_height'] = $this->ctx->getOption('filemanager_thumb_height', 80);
                    $fileArray['preview'] = 0;
                }
                $files[$fileName] = $fileArray;
                $files[$fileName]['menu'] = $this->getListContextMenu($file, false, $files[$fileName]);
            }
        }

        // array_multisort($filenames, SORT_ASC, SORT_STRING, $files);

        $ls = array();
        array_multisort($filenames, SORT_ASC, SORT_STRING, $files);

        foreach ($files as $file) {
            $ls[] = $file;
        }

        return $ls;
    }

    /**
     * Create a Container
     *
     * @param string $name
     * @param string $parentContainer
     * @return boolean
     */
    public function createContainer($name,$parentContainer) {
        $newPath = ltrim($parentContainer.rtrim($name,'/').'/', '/');
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
     * @param $path
     * @return boolean
     */
    public function removeContainer($path) {
        if (!$this->driver->if_object_exists($this->bucket,$path)) {
            $this->addError('file',$this->xpdo->lexicon('file_folder_err_ns').': '.$path);
            return false;
        }

        /* remove file from s3 */
        $deleted = $this->driver->delete_object($this->bucket,$path);

        /* log manager action */
        $this->xpdo->logManagerAction('directory_remove','',$path);

        return !empty($deleted);
    }

    /**
     * Check that the filename has a file type extension that is allowed
     *
     * @param $filename
     * @return bool
     */
    public function checkFiletype($filename) {
        if ($this->getOption('allowedFileTypes')) {
            $allowedFileTypes = explode(',', $this->getOption('allowedFileTypes'));
        } else {
            $allowedFiles = $this->xpdo->getOption('upload_files') ? explode(',', $this->xpdo->getOption('upload_files')) : array();
            $allowedImages = $this->xpdo->getOption('upload_images') ? explode(',', $this->xpdo->getOption('upload_images')) : array();
            $allowedMedia = $this->xpdo->getOption('upload_media') ? explode(',', $this->xpdo->getOption('upload_media')) : array();
            $allowedFlash = $this->xpdo->getOption('upload_flash') ? explode(',', $this->xpdo->getOption('upload_flash')) : array();
            $allowedFileTypes = array_unique(array_merge($allowedFiles, $allowedImages, $allowedMedia, $allowedFlash));
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
     * Create a file
     *
     * @param string $objectPath
     * @param string $name
     * @param string $content
     * @return boolean|string
     */
    public function createObject($objectPath,$name,$content) {
        /* check to see if file already exists */
        if ($this->driver->if_object_exists($this->bucket,$objectPath.$name)) {
            $this->addError('file',sprintf($this->xpdo->lexicon('file_err_ae'),$objectPath.$name));
            return false;
        }

        if (!$this->checkFiletype($objectPath.$name)) {
            return false;
        }

        /* create empty file that acts as folder */
        $created = $this->driver->create_object($this->bucket,$objectPath.$name,array(
                'body' => $content,
                'acl' => AmazonS3::ACL_PUBLIC,
                'length' => 0,
           ));

        if (!$created) {
            $this->addError('name',$this->xpdo->lexicon('file_err_create').$objectPath.$name);
            return false;
        }

        $this->xpdo->logManagerAction('file_create','',$objectPath.$name);
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
        if (!$this->checkFiletype($objectPath)) {
            return false;
        }
        $created = $this->driver->create_object($this->bucket,$objectPath,array(
                 'body' => $content,
                 'acl' => AmazonS3::ACL_PUBLIC,
                 'length' => 0,
            ));

        if (!$created) {
            $this->addError('name',$this->xpdo->lexicon('file_err_create').$objectPath);
            return false;
        }

        $this->xpdo->logManagerAction('file_create','',$objectPath);
        return true;
    }


    /**
     * Delete a file from S3
     *
     * @param string $objectPath
     * @return boolean
     */
    public function removeObject($objectPath) {
        if (!$this->checkFiletype($objectPath)) {
            return false;
        }
        if (!$this->driver->if_object_exists($this->bucket,$objectPath)) {
            $this->addError('file',$this->xpdo->lexicon('file_folder_err_ns').': '.$objectPath);
            return false;
        }

        /* remove file from s3 */
        $deleted = $this->driver->delete_object($this->bucket,$objectPath);

        /* log manager action */
        $this->xpdo->logManagerAction('file_remove','',$objectPath);

        return !empty($deleted);
    }

    /**
     * Rename/move a file
     *
     * @param string $oldPath
     * @param string $newName
     * @return bool
     */
    public function renameObject($oldPath,$newName) {
        if (!$this->driver->if_object_exists($this->bucket,$oldPath)) {
            $this->addError('file',$this->xpdo->lexicon('file_folder_err_ns').': '.$oldPath);
            return false;
        }

        if (!$this->checkFiletype($newName)) {
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

    /**
     * Upload files to S3
     *
     * @param string $container
     * @param array $objects
     * @return bool
     */
    public function uploadObjectsToContainer($container,array $objects = array()) {
        if ($container == '/' || $container == '.') $container = '';

        $maxFileSize = $this->xpdo->getOption('upload_maxsize',null,1048576);

        /* loop through each file and upload */
        foreach ($objects as $file) {
            if ($file['error'] != 0) continue;
            if (empty($file['name'])) continue;
            $ext = pathinfo($file['name'],PATHINFO_EXTENSION);
            $ext = strtolower($ext);

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

            $newPath = $container.$file['name'];


            $contentType = $this->getContentType($ext);
            $uploaded = $this->driver->create_object($this->bucket,$newPath,array(
                'fileUpload' => $file['tmp_name'],
                'acl' => AmazonS3::ACL_PUBLIC,
                'length' => $size,
                'contentType' => $contentType,
            ));

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
            'png' => 'image/png',
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
     * @param string $point
     * @return boolean
     */
    public function moveObject($from,$to,$point = 'append') {
        $this->xpdo->lexicon->load('source');
        $success = false;

        if (substr(strrev($from),0,1) == '/') {
            $this->xpdo->error->message = $this->xpdo->lexicon('s3_no_move_folder',array(
                'from' => $from
            ));
            return $success;
        }

        if (!$this->driver->if_object_exists($this->bucket,$from)) {
            $this->xpdo->error->message = $this->xpdo->lexicon('file_err_ns').': '.$from;
            return $success;
        }

        if ($to != '/') {
            if (!$this->driver->if_object_exists($this->bucket,$to)) {
                $this->xpdo->error->message = $this->xpdo->lexicon('file_err_ns').': '.$to;
                return $success;
            }
            $toPath = rtrim($to,'/').'/'.basename($from);
        } else {
            $toPath = basename($from);
        }

        $response = $this->driver->copy_object(array(
            'bucket' => $this->bucket,
            'filename' => $from,
        ),array(
            'bucket' => $this->bucket,
            'filename' => $toPath,
        ),array(
            'acl' => AmazonS3::ACL_PUBLIC,
        ));
        $success = $response->isOK();

        if ($success) {
            $deleteResponse = $this->driver->delete_object($this->bucket,$from);
            $success = $deleteResponse->isOK();
        } else {
            $this->xpdo->error->message = $this->xpdo->lexicon('file_folder_err_rename').': '.$to.' -> '.$from;
        }

        return $success;
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
            'key' => array(
                'name' => 'key',
                'desc' => 'prop_s3.key_desc',
                'type' => 'password',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ),
            'secret_key' => array(
                'name' => 'secret_key',
                'desc' => 'prop_s3.secret_key_desc',
                'type' => 'password',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ),
            'imageExtensions' => array(
                'name' => 'imageExtensions',
                'desc' => 'prop_s3.imageExtensions_desc',
                'type' => 'textfield',
                'value' => 'jpg,jpeg,png,gif,svg',
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
            'region' => array(
                'name' => 'region',
                'desc' => 'prop_s3.region_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
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
        $properties = $this->getPropertyList();
        if (strpos($src,$properties['url']) === false) {
            $src = $properties['url'].ltrim($src,'/');
        }
        return $src;
    }

    /**
     * Get the base URL for this source. Only applicable to sources that are streams.
     *
     * @param string $object An optional object to find the base url of
     * @return string
     */
    public function getBaseUrl($object = '') {
        $properties = $this->getPropertyList();
        return $properties['url'];
    }

    /**
     * Get the absolute URL for a specified object. Only applicable to sources that are streams.
     *
     * @param string $object
     * @return string
     */
    public function getObjectUrl($object = '') {
        $properties = $this->getPropertyList();
        return $properties['url'].$object;
    }

    public function getObjectFileSize($filename) {
        return $this->driver->get_object_filesize($this->bucket, $filename);
    }

    /**
     * Tells if a file is a binary file or not.
     *
     * @param string $file
     * @param boolean If the passed string in $file is actual file content
     * @return boolean True if a binary file.
     */
    public function isBinary($file, $isContent = false) {
        if(!$isContent) {
            // $file = file_get_contents($file, null, $stream, null, 512);
            // this code is taken from modfilehandler.class.php method isBinary()
            // the code above results in false positives (e.g. files marked as binary if the aren't)
            $fh = @fopen($file, 'r');
            $blk = @fread($fh, 512);
            @fclose($fh);
            @clearstatcache();
            return (substr_count($blk, "^ -~" /*. "^\r\n"*/) / 512 > 0.3) || (substr_count($blk, "\x00") > 0) ? false : true;
        }

        $content = str_replace(array("\n", "\r", "\t"), '', $file);
        return ctype_print($content) ? false : true;
    }

    /**
     * Get the contents of a specified file
     *
     * @param string $objectPath
     * @return array
     */
    public function getObjectContents($objectPath) {
        $properties = $this->getPropertyList();
        $objectUrl = $properties['url'].$objectPath;
        $contents = @file_get_contents($objectUrl);

        $imageExtensions = $this->getOption('imageExtensions',$this->properties,'jpg,jpeg,png,gif,svg');
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
}
