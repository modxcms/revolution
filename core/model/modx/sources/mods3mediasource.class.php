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

        $directories = array();
        $files = array();
        foreach ($list as $idx => $path) {
            if ($path == $dir) continue;
            $fileName = basename($path);
            $isDir = substr(strrev($path),0,1) === '/';

            $relativePath = $path == '/' ? $path : str_replace($dir,'',$path);
            $slashCount = substr_count($relativePath,'/');
            if (($slashCount > 1 && $isDir) || ($slashCount > 0 && !$isDir)) {
                continue;
            }
            if ($isDir) {
                $directories[$path] = array(
                    'id' => $path,
                    'text' => $fileName,
                    'cls' => '',//implode(' ',$cls),
                    'type' => 'dir',
                    'leaf' => false,
                    'path' => $path,
                    'pathRelative' => $path,
                    'perms' => '',
                    'menu' => array(),
                );
            } else {
                $files[$path] = array(
                    'id' => $path,
                    'text' => $fileName,
                    'cls' => '',//implode(' ',$cls),
                    'type' => 'file',
                    'leaf' => true,
                    //'qtip' => in_array($ext,$imagesExts) ? '<img src="'.$fromManagerUrl.'" alt="'.$fileName.'" />' : '',
                    //'perms' => $octalPerms,
                    'path' => $path,
                    'pathRelative' => $path,
                    'directory' => $path,
                    'url' => $properties['url']['value'].$properties['bucket']['value'].'/'.$path,
                    'file' => $path,
                    'menu' => array(),
                );
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