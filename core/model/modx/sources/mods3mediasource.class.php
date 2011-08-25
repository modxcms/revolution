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
     * @param string $dir
     * @return array
     */
    public function getFolderList($dir) {
        $c = array();
        if ($dir == '' || $dir == '/') {
            $c['pcre'] = '/^[^\/]*\/?$/';
        } else {
            $c['prefix'] = $dir;
        }
        $list = $this->driver->get_object_list($this->bucket,$c);

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
            //var_dump($relativePath.' = '.substr_count($relativePath,'/'));
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
                    'url' => $path,
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

    /**
     * Get the description of this source type
     * @return string
     */
    public function getTypeDescription() {
        $this->xpdo->lexicon->load('source');
        return $this->xpdo->lexicon('source_type.s3_desc');
    }
}