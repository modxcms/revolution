<?php
/**
 * @package modx
 * @subpackage sources
 */
use xPDO\xPDO;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

require_once MODX_CORE_PATH . 'model/modx/sources/modmediasource.class.php';
/**
 * Implements an Amazon S3-based media source, allowing basic manipulation, uploading and URL-retrieval of resources
 * in a specified S3 bucket.
 *
 * @package modx
 * @subpackage sources
 */
class modS3MediaSource extends modMediaSource {
    /** @var string $bucket */
    protected $bucket;

    /** @var  string ~ a folder to load into */
    protected $prefix;

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
        parent::initialize();
        $properties = $this->getPropertyList();

        $this->bucket = $this->xpdo->getOption('bucket', $properties, '');
        $this->prefix = $this->xpdo->getOption('prefix', $properties, '');

        $config = [
            'credentials' => [
                'key'    => $this->xpdo->getOption('key', $properties, ''),
                'secret' => $this->xpdo->getOption('secret_key', $properties, ''),
            ],
            // region is required
            'region' => $this->xpdo->getOption('region', $properties, 'us-east-2'),
            'version' => $this->xpdo->getOption('version', $properties, '2006-03-01'),
        ];

        $client = new S3Client($config);

        $s3Adapter = new AwsS3Adapter($client, $this->bucket, $this->prefix);

        $this->loadFlySystem($s3Adapter);
        return true;
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
            'region' => array(
                'name' => 'region',
                'desc' => 'prop_s3.region_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => 'us-east-2',
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
            'prefix' => array(
                'name' => 'prefix',
                'desc' => 'prop_s3.prefix_desc',
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
            'visibility' => array(
                'name' => 'visibility',
                'desc' => 'prop_file.visibility_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => 'public',
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

    // @TODO review, are any of the below needed?
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
}
