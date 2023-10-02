<?php

namespace MODX\Revolution\Sources;

use Aws\S3\S3Client;
use Exception;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToDeleteDirectory;
use League\Flysystem\UnableToMoveFile;
use League\Flysystem\UnableToReadFile;
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
                'value' => 'jpg,jpeg,png,gif,svg,webp',
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
            if (gettype($v) !== 'array') {
                continue;
            }
            if ($v['handler'] === 'this.renameDirectory') {
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
            $this->addError('source', $this->xpdo->lexicon('no_move_folder'));
        } catch (FilesystemException | UnableToRetrieveMetadata $e) {
            // on S3 Directory Mime Types are unreadable
            $mimeType = 'directory';
        }
        if ($mimeType !== 'directory') {
            return parent::moveObject($from, $to, $point, $to_source);
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
        if ($path === DIRECTORY_SEPARATOR || $path === '\\') {
            $path = '';
        }

        $bases = $this->getBases($path);
        $imageExtensions = explode(',', $properties['imageExtensions']);
        $skipFiles = $this->getSkipFilesArray($properties);
        $allowedExtensions = $this->getAllowedExtensionsArray($properties);

        $directories = $dirNames = $files = $fileNames = [];

        if (!empty($path)) {
            // Ensure the provided path can be read.
            try {
                $mimeType = $this->filesystem->mimeType($path);
            } catch (FilesystemException | UnableToRetrieveMetadata $e) {
                // on S3 Directory Mime Types are unreadable
                $mimeType = 'directory';
            }

            if ($mimeType !== 'directory') {
                $this->addError('path', $this->xpdo->lexicon('file_folder_err_invalid'));
                return [];
            }
        }

        try {
            /** @var array $contents */
            $contents = $this->filesystem->listContents($path);
        } catch (Exception $e) {
            $this->addError('path', $e->getMessage());
            return [];
        }

        $re = '#^(.*?/|)(' . implode('|', array_map('preg_quote', $skipFiles)) . ')/?$#';
        $pathid = rawurlencode(rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
        foreach ($contents as $object) {
            $id = rawurlencode(rtrim($object['path'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
            if ($id === $pathid || preg_match($re, $object['path'])) {
                continue;
            }
            $file_name = basename($object['path']);

            if (
                    $object['type'] === 'dir' &&
                    $this->hasPermission('directory_list')
            ) {
                $cls = $this->getExtJSDirClasses();
                $dirNames[] = strtoupper($file_name);
                $directories[$file_name] = [
                        'id' => $id,
                        'sid' => $this->get('id'),
                        'text' => $file_name,
                        'cls' => implode(' ', $cls),
                        'iconCls' => 'icon icon-folder',
                        'type' => 'dir',
                        'leaf' => false,
                        'path' => $object['path'],
                        'pathRelative' => $object['path'],
                        'menu' => [
                            'items' => $this->getListDirContextMenu(),
                        ],
                        'visibility' => true
                    ];
            } elseif (
                    $object['type'] === 'file' &&
                    !$properties['hideFiles'] &&
                    $this->hasPermission('file_list')
            ) {
                $ext = pathinfo($object['path'], PATHINFO_EXTENSION);
                $ext = $properties['use_multibyte']
                        ? mb_strtolower($ext, $properties['modx_charset'])
                        : strtolower($ext);
                if (
                        !empty($allowedExtensions) &&
                        !in_array($ext, $allowedExtensions, true)
                ) {
                    continue;
                }
                $fileNames[] = strtoupper($file_name);
                $files[$file_name] = $this->buildFileList(
                    $object['path'],
                    $ext,
                    $imageExtensions,
                    $bases,
                    $properties
                );
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

        if (!empty($path) && $path !== DIRECTORY_SEPARATOR) {
            try {
                $mimeType = $this->filesystem->mimeType($path);
            } catch (FilesystemException | UnableToRetrieveMetadata $e) {
                // on S3 Directory Mime Types are unreadable
                $mimeType = 'directory';
            }

            // Ensure this is a directory.
            if ($mimeType !== 'directory') {
                $this->addError('path', $this->xpdo->lexicon('file_folder_err_invalid'));
                return [];
            }
        }

        try {
            $contents = $this->filesystem->listContents($path);
        } catch (FilesystemException $e) {
            $this->addError('path', $e->getMessage());
            $this->xpdo->log(modX::LOG_LEVEL_ERROR, $e->getMessage());
            return [];
        }
        foreach ($contents as $object) {
            if (
                (in_array($fullPath . $object['path'], $skipFiles, true)) ||
                in_array($object['path'], $skipFiles, true) ||
                in_array(trim($object['path'], DIRECTORY_SEPARATOR), $skipFiles, true)
            ) {
                continue;
            }
            if ($object instanceof DirectoryAttributes && !$this->hasPermission('directory_list')) {
                continue;
            }

            if ($object['type'] === 'file' && !$properties['hideFiles'] && $this->hasPermission('file_list')) {
                $ext = pathinfo($object['path'], PATHINFO_EXTENSION);
                $ext = $properties['use_multibyte']
                    ? mb_strtolower($ext, $properties['modx_charset'])
                    : strtolower($ext);
                if (!empty($allowedExtensions) && !in_array($ext, $allowedExtensions, true)) {
                    continue;
                }
                $fileNames[] = strtoupper($object['path']);

                $files[$object['path']] = $this->buildFileBrowserViewList(
                    $object['path'],
                    $ext,
                    $imageExtensions,
                    $bases,
                    $properties
                );
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


    /**
     * Remove a folder at the specified location
     *
     * @param string $path ~ pre 3.0 $path was full absolute path, all paths need to be relative
     *
     * @return boolean
     */
    public function removeContainer($path)
    {
        $path = $this->postfixSlash($path);

        // Ensure this is a directory.
        try {
            $mimeType = $this->filesystem->mimeType($path);
        } catch (FilesystemException | UnableToReadFile $e) {
            // on S3 Directory Mime Types are unreadable
            $mimeType = 'directory';
        }
        if ($mimeType !== 'directory') {
            $this->addError('path', $this->xpdo->lexicon('file_folder_err_invalid'));
            return false;
        }

        // Attempt deletion of the directory.
        try {
            $this->filesystem->deleteDirectory($path);
        } catch (FilesystemException | UnableToDeleteDirectory $e) {
            $this->addError('path', $this->xpdo->lexicon('file_folder_err_remove'));
            $this->xpdo->log(modX::LOG_LEVEL_ERROR, $e->getMessage());
            return false;
        }

        $this->xpdo->invokeEvent('OnFileManagerDirRemove', [
            'directory' => $path,
            'source' => &$this,
        ]);
        $this->xpdo->logManagerAction('directory_remove', '', "{$this->get('name')}: $path");

        return true;
    }


    /**
     * @param string $oldPath
     * @param string $newName
     *
     * @return bool
     */
    public function renameContainer($oldPath, $newName)
    {
        $oldPath = trim($oldPath, DIRECTORY_SEPARATOR);
        if (strpos($oldPath, DIRECTORY_SEPARATOR)) {
            $path = explode(DIRECTORY_SEPARATOR, $oldPath);
            array_pop($path);
            $newPath = implode(DIRECTORY_SEPARATOR, $path) . DIRECTORY_SEPARATOR . $newName;
        } else {
            $newPath = $newName;
        }
        $oldPath = $this->sanitizePath($oldPath) . DIRECTORY_SEPARATOR;
        $newPath = $this->sanitizePath($newPath) . DIRECTORY_SEPARATOR;

        // Ensure current directory can be read.
        try {
            $mimeType = $this->filesystem->mimeType($oldPath);
        } catch (FilesystemException | UnableToRetrieveMetadata $e) {
            // on S3 Directory Mime Types are unreadable
            $mimeType = 'directory';
        }
        if ($mimeType !== 'directory') {
            $this->addError('name', $this->xpdo->lexicon('file_folder_err_invalid'));
            return false;
        }

        // Ensure new path doesn't exist.
        try {
            if ($this->filesystem->fileExists($newPath)) {
                $this->addError('name', $this->xpdo->lexicon('file_folder_err_ae'));
                return false;
            }
        } catch (FilesystemException | UnableToRetrieveMetadata $e) {
            $this->xpdo->log(modX::LOG_LEVEL_ERROR, $e->getMessage());
            return false;
        }

        // Attempt renaming the directory. Flysystem considers this a "move".
        try {
            $this->filesystem->move($oldPath, $newPath);
        } catch (FilesystemException | UnableToMoveFile $e) {
            $this->addError('name', $this->xpdo->lexicon('file_folder_err_rename'));
            $this->xpdo->log(modX::LOG_LEVEL_ERROR, $e->getMessage());
            return false;
        }

        $this->xpdo->invokeEvent('OnFileManagerDirRename', [
            'directory' => $newPath,
            'source' => &$this,
        ]);
        $this->xpdo->logManagerAction('directory_rename', '', "{$this->get('name')}: $oldPath -> $newPath");

        return true;
    }


    /**
     * @param string $path ~ relative path of file/directory
     *
     * @return bool
     */
    public function getVisibility($path)
    {
        // S3 Visibility Checks always returning false
        return true;
    }


    /**
     * @param string $path ~ relative path of file/directory
     * @param string $visibility ~ public or private
     *
     * @return bool
     */
    public function setVisibility($path, $visibility)
    {
        // S3 Set visibility always returns false
        return false;
    }

    protected function getImageDimensions($path, $ext)
    {
        return false;
    }

    protected function isFileBinary($file)
    {
        $binary_extensions = [
            'css',
            'csv',
            'htm',
            'html',
            'ics',
            'ini',
            'js',
            'json',
            'less',
            'log',
            'md',
            'mjs',
            'php',
            'sh',
            'scss',
            'sql',
            'tpl',
            'tsv',
            'txt',
            'xml',
        ];
        foreach ($binary_extensions as $a) {
            if (stripos($file, $a) !== false) {
                return true;
            }
        }
        return false;
    }

    protected function isFileImage($file, $image_extensions = [])
    {
        foreach ($image_extensions as $a) {
            if (stripos($file, $a) !== false) {
                return true;
            }
        }
        return false;
    }


    /**
     * @param string $path
     * @param string $ext
     * @param array $image_extensions
     * @param array $bases
     * @param array $properties
     *
     * @return array
     */
    protected function buildFileBrowserViewList($path, $ext, $image_extensions, $bases, $properties)
    {
        $editAction = $this->getEditActionId();

        $page = null;
        if (!$this->isFileBinary($path)) {
            $page = !empty($editAction)
                ? '?a=' . $editAction .
                    '&file=' . $path .
                    '&wctx=' . $this->ctx->get('key') .
                    '&source=' . $this->get('id')
                : null;
        }

        $width = $this->ctx->getOption('filemanager_image_width', 800);
        $height = $this->ctx->getOption('filemanager_image_height', 600);

        $thumb_width = $this->ctx->getOption('filemanager_thumb_width', 100);
        $thumb_height = $this->ctx->getOption('filemanager_thumb_height', 80);

        $preview = 0;
        if ($this->isFileImage($path, $image_extensions)) {
            $preview = 1;
            $preview_image_info = $this->buildManagerImagePreview($path, $ext, $width, $height, $bases, $properties);
        }

        $visibility = $this->visibility_files ? $this->getVisibility($path) : false;

        $lastmod = 0;
        $size = 0;
        $file_list = [
            'id' => $path,
            'sid' => $this->get('id'),
            'name' => basename($path),
            'cls' => 'icon-' . $ext,
            // preview
            'preview' => $preview,
            'image' => $preview_image_info['src'] ?? '',
            // thumb
            'thumb' => $preview_image_info['src'] ?? '',
            'thumb_width' => $thumb_width,
            'thumb_height' => $thumb_height,

            'url' => $path,
            'relativeUrl' => ltrim($path, DIRECTORY_SEPARATOR),
            'fullRelativeUrl' => rtrim($bases['url']) . ltrim($path, DIRECTORY_SEPARATOR),
            'ext' => $ext,
            'pathname' => $path,
            'pathRelative' => rawurlencode($path),

            'lastmod' => $lastmod,
            'disabled' => false,
            'leaf' => true,
            'page' => $page,
            'size' => $size,
            'menu' => $this->getListFileContextMenu($path, !empty($page)),
        ];
        if ($this->visibility_files && $visibility) {
            $file_list['visibility'] = $visibility;
        }

        return $file_list;
    }
}
