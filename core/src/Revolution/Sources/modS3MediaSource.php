<?php

namespace MODX\Revolution\Sources;

use Aws\S3\S3Client;
use Exception;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToRetrieveMetadata;
use League\Flysystem\Visibility;
use MODX\Revolution\modX;
use xPDO\xPDO;

/**
 * Implements an Amazon S3-based media source, allowing basic manipulation, uploading and URL-retrieval of resources
 * in a specified S3 bucket.
 *
 * @package MODX\Revolution\Sources
 */
class modS3MediaSource extends modMediaSource
{
    protected $visibility_files = false;
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
        $endpoint = $this->xpdo->getOption('endpoint', $properties, '');

        $config = [
            'credentials' => [
                'key' => $this->xpdo->getOption('key', $properties, ''),
                'secret' => $this->xpdo->getOption('secret_key', $properties, ''),
            ],
            'region' => $this->xpdo->getOption('region', $properties, 'us-east-2'),
            'version' => $this->xpdo->getOption('version', $properties, '2006-03-01'),
        ];

        if (!empty($endpoint)) {
            $config['endpoint'] = $endpoint;
        }

        try {
            $client = new S3Client($config);
            if (!$client->doesBucketExist($bucket)) {
                $this->xpdo->log(
                    xPDO::LOG_LEVEL_ERROR,
                    $this->xpdo->lexicon('source_err_init', ['source' => $this->get('name')])
                );

                return false;
            }
            $adapter = new AwsS3V3Adapter(new S3Client($config), $bucket, $prefix);
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
            'endpoint' => [
                'name'    => 'endpoint',
                'desc'    => 'prop_s3.endpoint_desc',
                'type'    => 'textfield',
                'options' => '',
                'value'   => '',
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
            $mimeType = $this->filesystem->mimeType($path);
            if ($mimeType !== 'directory') {
                return parent::moveObject($from, $to, $point, $to_source);
            }
            $this->addError('source', $this->xpdo->lexicon('no_move_folder'));
        } catch (FilesystemException | UnableToRetrieveMetadata $e) {
            $this->addError('path', $this->xpdo->lexicon('file_err_nf'));
            $this->xpdo->log(modX::LOG_LEVEL_ERROR, $e->getMessage());
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
        return [
            'src' => $this->getObjectUrl($path),
            'width' => $width,
            'height' => $height,
        ];
    }

    /**
     * Return an array of files and folders at this current level in the directory structure
     *
     * @param string $path
     *
     * @return array
     */
    public function getContainerList($path)
    {
        $properties = $this->getPropertyListWithDefaults();
        $path = $this->postfixSlash($path);
        if ($path == DIRECTORY_SEPARATOR || $path == '\\') {
            $path = '';
        }

        $bases = $this->getBases($path);
        $imageExtensions = explode(',', $properties['imageExtensions']);
        $skipFiles = $this->getSkipFilesArray($properties);
        $allowedExtensions = $this->getAllowedExtensionsArray($properties);

        $directories = $dirNames = $files = $fileNames = [];

        try {
            $re = '#^(.*?/|)(' . implode('|', array_map('preg_quote', $skipFiles)) . ')/?$#';
            /** @var array $contents */
            $contents = $this->filesystem->listContents($path);
            $pathid = rawurlencode(rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
            foreach ($contents as $object) {
                $id = rawurlencode(rtrim($object['path'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
                if (preg_match($re, $object['path']) || $id == $pathid) {
                    continue;
                }
                $file_name = basename($object['path']);

                if ($object['type'] == 'dir' && $this->hasPermission('directory_list')) {
                    $cls = $this->getExtJSDirClasses();
                    $dirNames[] = strtoupper($file_name);
                    $visibility = $this->visibility_dirs ? $this->getVisibility($object['path']) : false;
                    $directories[$file_name] = [
                        'id' => $id,
                        'sid' => $this->get('id'),
                        'text' => $file_name,
                        'cls' => implode(' ', $cls),
                        'iconCls' => 'icon ' . ($visibility == Visibility::PRIVATE
                                ? 'icon-eye-slash' : 'icon-folder'),
                        'type' => 'dir',
                        'leaf' => false,
                        'path' => $object['path'],
                        'pathRelative' => $object['path'],
                        'menu' => [],
                    ];
                    if ($this->visibility_dirs && $visibility) {
                        $directories[$file_name]['visibility'] = $visibility;
                    }
                    $directories[$file_name]['menu'] = [
                        'items' => $this->getListDirContextMenu(),
                    ];
                } elseif ($object['type'] == 'file' && !$properties['hideFiles'] && $this->hasPermission('file_list')) {
                    // @TODO review/refactor extension and mime_type would be better for filesystems that
                    // may not always have an extension on it. For example would be S3 and you have an HTML file
                    // but the name is just myPage - $this->filesystem->getMimetype($object['path']);
                    $ext = pathinfo($object['path'], PATHINFO_EXTENSION);
                    $ext = $properties['use_multibyte']
                        ? mb_strtolower($ext, $properties['modx_charset'])
                        : strtolower($ext);
                    if (!empty($allowedExtensions) && !in_array($ext, $allowedExtensions)) {
                        continue;
                    }
                    $fileNames[] = strtoupper($file_name);
                    $files[$file_name] = $this->buildFileList($object['path'], $ext, $imageExtensions, $bases, $properties);
                }
            }

            $ls = [];
            // now sort files/directories
            array_multisort($dirNames, SORT_ASC, SORT_STRING, $directories);
            foreach ($directories as $dir) {
                $ls[] = $dir;
            }

            array_multisort($fileNames, SORT_ASC, SORT_STRING, $files);
            foreach ($files as $file) {
                $ls[] = $file;
            }

            return $ls;
        } catch (Exception $e) {
            $this->addError('path', $e->getMessage());

            return [];
        }
    }


    /**
     * Get a list of files in a specific directory.
     *
     * @param string $path
     *
     * @return array
     */
    public function getObjectsInContainer($path)
    {
        $properties = $this->getPropertyListWithDefaults();
        $path = $this->postfixSlash($path);
        $bases = $this->getBases($path);

        $fullPath = $path;
        if (!empty($bases['pathAbsolute'])) {
            $fullPath = $bases['pathAbsolute'] . ltrim($path, DIRECTORY_SEPARATOR);
        }

        $imageExtensions = explode(',', $properties['imageExtensions']);
        $skipFiles = $this->getSkipFilesArray($properties);

        $allowedExtensions = $this->getAllowedExtensionsArray($properties);

        $files = $fileNames = [];

        try {
            $contents = $this->filesystem->listContents($path);
        } catch (FilesystemException $e) {
            $this->addError('path', $e->getMessage());
            $this->xpdo->log(modX::LOG_LEVEL_ERROR, $e->getMessage());
            return [];
        }
        foreach ($contents as $object) {
            if (in_array($object['path'], $skipFiles) || in_array(trim($object['path'], DIRECTORY_SEPARATOR), $skipFiles) || (in_array($fullPath . $object['path'], $skipFiles))) {
                continue;
            }
            if ($object['type'] == 'dir' && !$this->hasPermission('directory_list')) {
                continue;
            } elseif ($object['type'] == 'file' && !$properties['hideFiles'] && $this->hasPermission('file_list')) {
                // @TODO review/refactor ext and mime_type would be better for filesystems that may not always have an extension on it
                // example would be S3 and you have an HTML file but the name is just myPage
                //$this->filesystem->getMimetype($object['path']);
                $ext = pathinfo($object['path'], PATHINFO_EXTENSION);
                $ext = $properties['use_multibyte']
                    ? mb_strtolower($ext, $properties['modx_charset'])
                    : strtolower($ext);
                if (!empty($allowedExtensions) && !in_array($ext, $allowedExtensions)) {
                    continue;
                }
                $fileNames[] = strtoupper($object['path']);

                $files[$object['path']] = $this->buildFileBrowserViewList($object['path'], $ext, $imageExtensions, $bases, $properties);
            }
        }

        $ls = [];
        // now sort files/directories
        array_multisort($fileNames, SORT_ASC, SORT_STRING, $files);
        foreach ($files as $file) {
            $ls[] = $file;
        }

        return $ls;
    }
}
