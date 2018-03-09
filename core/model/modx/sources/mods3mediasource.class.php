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
class modS3MediaSource extends modMediaSource
{
    /** @var string $bucket */
    protected $bucket;

    /** @var  string ~ a folder to load into */
    protected $prefix;
    protected $visibility_files = true;
    protected $visibility_dirs = false;


    /**
     * @return bool
     */
    public function initialize()
    {
        parent::initialize();
        $properties = $this->getPropertyList();

        $this->bucket = $this->xpdo->getOption('bucket', $properties, '');
        $this->prefix = $this->xpdo->getOption('prefix', $properties, '');

        $config = [
            'credentials' => [
                'key' => $this->xpdo->getOption('key', $properties, ''),
                'secret' => $this->xpdo->getOption('secret_key', $properties, ''),
            ],
            // region is required
            'region' => $this->xpdo->getOption('region', $properties, 'us-east-2'),
            'version' => $this->xpdo->getOption('version', $properties, '2006-03-01'),
        ];
        try {
            $client = new S3Client($config);
            if (!$client->doesBucketExist($this->bucket)) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $this->xpdo->lexicon('source_err_init'));

                return false;
            }
            $this->adapter = new AwsS3Adapter(new S3Client($config), $this->bucket, $this->prefix);
            $this->loadFlySystem($this->adapter);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage());

            return false;
        }

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

        return $this->xpdo->lexicon('source_type.s3');
    }


    /**
     * Get the description of this source type
     *
     * @return string
     */
    public function getTypeDescription()
    {
        $this->xpdo->lexicon->load('source');

        return $this->xpdo->lexicon('source_type.s3_desc');
    }


    /**
     * @return array
     */
    public function getDefaultProperties()
    {
        return [
            'url' => [
                'name' => 'url',
                'desc' => 'prop_s3.url_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => 'http://mysite.s3.amazonaws.com/',
                'lexicon' => 'core:source',
            ],
            'region' => [
                'name' => 'region',
                'desc' => 'prop_s3.region_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => 'us-east-2',
                'lexicon' => 'core:source',
            ],
            'bucket' => [
                'name' => 'bucket',
                'desc' => 'prop_s3.bucket_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ],
            'prefix' => [
                'name' => 'prefix',
                'desc' => 'prop_s3.prefix_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ],
            'key' => [
                'name' => 'key',
                'desc' => 'prop_s3.key_desc',
                'type' => 'password',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ],
            'secret_key' => [
                'name' => 'secret_key',
                'desc' => 'prop_s3.secret_key_desc',
                'type' => 'password',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ],
            'imageExtensions' => [
                'name' => 'imageExtensions',
                'desc' => 'prop_s3.imageExtensions_desc',
                'type' => 'textfield',
                'value' => 'jpg,jpeg,png,gif,svg',
                'lexicon' => 'core:source',
            ],
            'thumbnailType' => [
                'name' => 'thumbnailType',
                'desc' => 'prop_s3.thumbnailType_desc',
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
                'desc' => 'prop_s3.skipFiles_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '.svn,.git,_notes,nbproject,.idea,.DS_Store',
                'lexicon' => 'core:source',
            ],
        ];
    }


    /**
     * @return array
     */
    public function getListDirContextMenu()
    {
        $menu = parent::getListDirContextMenu();
        foreach ($menu as $k => $v) {
            if ($v['handler'] == 'this.renameDirectory') {
                unset($menu[$k]);
                $menu = array_values($menu);
                break;
            }
        }

        return $menu;
    }


    /**
     * @param string $from
     * @param string $to
     * @param string $point
     * @param int $to_source
     *
     * @return bool
     */
    public function moveObject($from, $to, $point = 'append', $to_source = 0)
    {
        $path = $this->postfixSlash($from);
        try {
            $originalObject = $this->filesystem->get($path);
            if ($originalObject->isFile()) {
                return parent::moveObject($from, $to, $point, $to_source);
            } else {
                $this->addError('source', $this->xpdo->lexicon('no_move_folder'));

                return false;
            }
        } catch (\xPDO\Exception\Container\NotFoundException $e) {
            $this->addError('path', $this->xpdo->lexicon('file_err_nf'));

            return false;
        } catch (Exception $e) {
            $this->addError('path', $e->getMessage());

            return false;
        }
    }


    /**
     * @param string $object An optional file to find the base path of
     *
     * @return string
     */
    public function getBasePath($object = '')
    {
        $properties = $this->getPropertyList();

        return $properties['url'];
    }


    /**
     * Prepare a src parameter to be rendered with phpThumb
     *
     * @param string $src
     *
     * @return string
     */
    public function prepareSrcForThumb($src)
    {
        $properties = $this->getPropertyList();
        if (strpos($src, $properties['url']) === false) {
            $src = $properties['url'] . ltrim($src, '/');
        }

        return $src;
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
        $properties = $this->getPropertyList();

        return $properties['url'];
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
        $properties = $this->getPropertyList();

        return !empty($properties['url'])
            ? rtrim($properties['url'], DIRECTORY_SEPARATOR) . '/' . $object
            : false;
    }


    /**
     * @param string $path
     * @param string $ext
     * @param int $width
     * @param int $height
     * @param array $bases
     * @param array $properties
     *
     * @return array
     */
    protected function buildManagerImagePreview($path, $ext, $width, $height, $bases, $properties = [])
    {
        if ($image = $this->getObjectUrl($path)) {
            if ($this->filesystem->getVisibility($path) != \League\Flysystem\AdapterInterface::VISIBILITY_PUBLIC) {
                $image = false;
            }
        }
        if (!$image) {
            $image = $this->ctx->getOption('manager_url', MODX_MANAGER_URL) . 'templates/default/images/restyle/nopreview.jpg';
        }

        return [
            'src' => $image,
            'width' => $width,
            'height' => $height,
        ];
    }

}
