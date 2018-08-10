<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Handles and encapsulates all S3 operations
 *
 * @package aws
 */
class modAws {
    /**
     * The AmazonS3 class instance
     * @var AmazonS3 $s3
     */
    public $s3 = null;
    /**
     * The current selected bucket
     * @var string $bucket
     */
    public $bucket = '';

    /**
     * @param modX $modx A reference to the modX instance
     * @param array $config An array of configuration properties
     */
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge(array(
        ),$config);

        if (!defined('AWS_KEY')) {
            define('AWS_KEY',$modx->getOption('aws.key',$config,''));
            define('AWS_SECRET_KEY',$modx->getOption('aws.secret_key',$config,''));
            /* (Not needed at this time)
            define('AWS_ACCOUNT_ID',$modx->getOption('aws.account_id',$config,''));
            define('AWS_CANONICAL_ID',$modx->getOption('aws.canonical_id',$config,''));
            define('AWS_CANONICAL_NAME',$modx->getOption('aws.canonical_name',$config,''));
            define('AWS_MFA_SERIAL',$modx->getOption('aws.mfa_serial',$config,''));
            define('AWS_CLOUDFRONT_KEYPAIR_ID',$modx->getOption('aws.cloudfront_keypair_id',$config,''));
            define('AWS_CLOUDFRONT_PRIVATE_KEY_PEM',$modx->getOption('aws.cloudfront_private_key_pem',$config,''));
            define('AWS_ENABLE_EXTENSIONS', 'false');*/
        }
        include dirname(__FILE__).DIRECTORY_SEPARATOR.'sdk.class.php';


        $this->getS3();
        $this->setBucket($modx->getOption('aws.default_bucket',$config,''));
    }

    /**
     * Get the AmazonS3 client class instance
     *
     * @return AmazonS3|null
     */
    public function getS3() {
        if (empty($this->s3)) {
            try {
                $this->s3 = new AmazonS3();
            } catch (Exception $e) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[modAws] Could not load AmazonS3 class: '.$e->getMessage());
            }
        }
        return $this->s3;
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
     * Check with S3 to confirm the currently selected bucket exists
     *
     * @return bool
     */
    public function bucketExists() {
        return $this->s3->if_bucket_exists($this->bucket);
    }

    /**
     * Attempt to create the bucket for the given region
     *
     * @param string $region
     * @return bool
     */
    public function createBucket($region = AmazonS3::REGION_US_W1) {
        $response = $this->s3->create_bucket($this->bucket,$region);
	    return $response->isOK() ? true : false;
    }

    /**
     * Upload a single item to S3
     *
     * @param array $file The PHP FILE array for the file
     * @param string $target The relative path in the bucket in which to place the file
     * @param array $options An array of options for uploading to S3
     * @return bool|string
     */
    public function uploadSingle($file,$target,array $options = array()) {
        $options = array_merge(array(
            'acl' => AmazonS3::ACL_PUBLIC,
        ),$options);

        if (is_array($file)) {
            $filename = basename($file['name']);
            $file = $file['tmp_name'];
        } else {
            $filename = basename($file);
        }
        $options['fileUpload'] = $file;
        $response = $this->s3->create_object($this->bucket,$target.$filename,$options);
        if ($response->status != 200) {
            return false;
        }
        return $this->s3->get_object_url($this->bucket,$target.$filename);
    }

    /**
     * Upload an array of files to S3
     *
     * @param $files The PHP $_FILES array which references the files to upload
     * @param string $target The relative path in the S3 bucket to upload to
     * @param array $options An array of options to pass to the S3 upload client
     * @return array|bool If ok, will return an array of URLs for the uploaded items
     */
    public function upload($files,$target = '',array $options = array()) {
        if (!is_array($files) || !empty($files['tmp_name'])) $files = array($files);

        $options = array_merge(array(
            'acl' => AmazonS3::ACL_PUBLIC,
        ),$options);

        $individualFiles = array();
        foreach ($files as $k => $file) {
            if (is_array($file)) {
                $filename = basename($file['name']);
                $file = $file['tmp_name'];
            } else {
                $filename = basename($file);
            }
            $individualFiles[] = $filename;
            $options['fileUpload'] = $file;
            $this->s3->batch()->create_object($this->bucket,$target.$filename,$options);
        }

        $response = $this->s3->batch()->send();
        if ($response->areOK()) {
            $data = array();
            foreach ($individualFiles as $filename) {
                $data[] = $this->s3->get_object_url($this->bucket,$target.$filename);
            }
            return $data;
        }
        return false;
    }

    /**
     * Get the S3 absolute URL for the specified path
     *
     * @param string $path The path by which to grab the URL
     * @param string $expires The time in which the URL expires
     * @return bool|mixed|string
     */
    public function getFileUrl($path,$expires = null) {
        return $this->s3->get_object_url($this->bucket,$path,$expires);
    }
}
