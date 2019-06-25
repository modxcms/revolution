<?php

namespace MODX\Revolution\Sources;

use Aws\S3\S3Client;
use Exception;
use League\Flysystem\AdapterInterface;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use xPDO\Exception\Container\NotFoundException;
use xPDO\xPDO;

/**
 * Implements an Amazon S3-based media source, allowing basic manipulation, uploading and URL-retrieval of resources
 * in a specified S3 bucket.
 *
 * @package MODX\Revolution\Sources
 */
class modS3MediaSource extends modMediaSource
{
    protected $visibility_files = true;
    protected $visibility_dirs = false;


    /**
     * @return bool
     */
    public function initialize()
    {
        parent::initialize();
        $properties = $this->getPropertyList();

        $bucket = $this->xpdo->getOption('bucket', $properties, '');
        $prefix = $this->xpdo->getOption('prefix', $properties, '');
        $config = [
            'credentials' => [
                'key' => $this->xpdo->getOption('key', $properties, ''),
                'secret' => $this->xpdo->getOption('secret_key', $properties, ''),
            ],
            'region' => $this->xpdo->getOption('region', $properties, 'us-east-2'),
            'version' => $this->xpdo->getOption('version', $properties, '2006-03-01'),
        ];
        try {
            $client = new S3Client($config);
            if (!$client->doesBucketExist($bucket)) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,
                    $this->xpdo->lexicon('source_err_init', ['source' => $this->get('name')]));

                return false;
            }
            $adapter = new AwsS3Adapter(new S3Client($config), $bucket, $prefix);
            $this->loadFlySystem($adapter);
        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $e->getMessage());

            return false;
        }

        return true;
    }


    /**
     * @return string
     */
    public function getTypeName()
    {
        $this->xpdo->lexicon->load('source');

        return $this->xpdo->lexicon('source_type.s3');
    }


    /**
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
    protected function getListDirContextMenu()
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
     * @param int    $to_source
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
            }
            $this->addError('source', $this->xpdo->lexicon('no_move_folder'));
        } catch (NotFoundException $e) {
            $this->addError('path', $this->xpdo->lexicon('file_err_nf'));
        } catch (Exception $e) {
            $this->addError('path', $e->getMessage());
        }

        return false;
    }


    /**
     * @param string $object
     *
     * @return string
     */
    public function getBasePath($object = '')
    {
        $properties = $this->getPropertyList();

        return $properties['url'];
    }


    /**
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
     * @param string $object
     *
     * @return string
     */
    public function getObjectUrl($object = '')
    {
        $properties = $this->getPropertyList();

        return !empty($properties['url'])
            ? rtrim($properties['url'], '/') . '/' . $object
            : false;
    }


    /**
     * @param string $path
     * @param string $ext
     * @param int    $width
     * @param int    $height
     * @param array  $bases
     * @param array  $properties
     *
     * @return array
     */
    protected function buildManagerImagePreview($path, $ext, $width, $height, $bases, $properties = [])
    {
        if ($image = $this->getObjectUrl($path)) {
            if ($this->getVisibility($path) != AdapterInterface::VISIBILITY_PUBLIC) {
                $image = false;
            }
        }

        return [
            'src' => $image,
            'width' => $width,
            'height' => $height,
        ];
    }
}
