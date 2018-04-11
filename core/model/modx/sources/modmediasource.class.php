<?php
/**
 * @package modx
 * @subpackage sources
 */
use xPDO\Cache\xPDOCacheManager;
use xPDO\Om\xPDOCriteria;
use xPDO\xPDO;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory as CacheStore;
use League\Flysystem\Cached\Storage\Predis;
use League\Flysystem\Cached\Storage\Memcached;

require_once 'modMediaSourceInterface.php';
/**
 * An abstract base class extend to implement loading your League\Flysystem\AbstractAdapter
 * See: https://flysystem.thephpleague.com/core-concepts/ and https://flysystem.thephpleague.com/creating-an-adapter/
 *
 * @package modx
 * @subpackage sources
 */
abstract class modMediaSource extends modAccessibleSimpleObject implements modMediaSourceInterface {
    /** @var modX|xPDO $xpdo */
    public $xpdo;
    /** @var modContext $ctx */
    protected $ctx;
    /** @var array $properties */
    protected $properties = array();
    /** @var array $permissions */
    protected $permissions = array();
    /** @var array $errors */
    protected $errors = array();

    /** @var modFileHandler */
    protected $fileHandler;

    /** @var bool to enable chmod menu option */
    protected $use_chmod = false;

    /** @var array  */
    protected $file_sizes = [];

    /** @var  FilesystemInterface */
    protected $adapter;

    /** @var  Filesystem */
    protected $filesystem;

    /**
     * Get the default MODX filesystem source
     * @static
     * @param xPDO|modX $xpdo A reference to an xPDO instance
     * @param int $defaultSourceId
     * @param boolean $fallbackToDefault
     * @return modMediaSource|null
     */
    public static function getDefaultSource(xPDO &$xpdo,$defaultSourceId = null,$fallbackToDefault = true) {
        if (empty($defaultSourceId)) {
            $defaultSourceId = $xpdo->getOption('default_media_source',null,1);
        }

        /** @var modMediaSource $defaultSource */
        $defaultSource = $xpdo->getObject('sources.modMediaSource',array(
            'id' => $defaultSourceId,
        ));
        if (empty($defaultSource) && $fallbackToDefault) {
            $c = $xpdo->newQuery('sources.modMediaSource');
            $c->sortby('id','ASC');
            $defaultSource = $xpdo->getObject('sources.modMediaSource',$c);
        }
        return $defaultSource;
    }

    /**
     * Get the current working context for the processor
     * @return bool|modContext
     */
    public function getWorkingContext() {
        $wctx = isset($this->properties['wctx']) && !empty($this->properties['wctx']) ? $this->properties['wctx'] : '';
        if (!empty($wctx)) {
            $workingContext = $this->xpdo->getContext($wctx);
            if (!$workingContext) {
                return false;
            }
        } else {
            $workingContext =& $this->xpdo->context;
        }
        $this->ctx =& $workingContext;
        return $this->ctx;
    }

    /**
     * Initialize the source
     * @return boolean
     */
    public function initialize() {
        $this->setProperties($this->getProperties(true));
        $this->getPermissions();

        // @TODO Only need to use legacy sanitize, review if Flysystem does this
        $options = array();
        if (!$this->ctx) {
            $this->ctx =& $this->xpdo->context;
        }
        $options['context'] = $this->ctx->get('key');
        /** @var modFileHandler fileHandler */
        $this->fileHandler = $this->xpdo->getService('fileHandler','modFileHandler', '',$options);
        return true;
    }

    /**
     * @return modContext
     */
    public function getContext()
    {
        return $this->ctx;
    }

    /**
     * Setup the request properties for the source, determining any request-specific actions
     * @param array $scriptProperties
     * @return array
     */
    public function setRequestProperties(array $scriptProperties = array()) {
        if (empty($this->properties)) $this->properties = array();
        $this->properties = array_merge($this->getPropertyList(),$this->properties,$scriptProperties);
        return $this->properties;
    }

    /**
     * Get a list of permissions for browsing and utilizing the source. May be overridden to provide a custom
     * list of permissions.
     * @return array
     */
    public function getPermissions() {
        $this->permissions = array(
            'directory_chmod' => $this->xpdo->hasPermission('directory_chmod'),
            'directory_create' => $this->xpdo->hasPermission('directory_create'),
            'directory_list' => $this->xpdo->hasPermission('directory_list'),
            'directory_remove' => $this->xpdo->hasPermission('directory_remove'),
            'directory_update' => $this->xpdo->hasPermission('directory_update'),
            'file_list' => $this->xpdo->hasPermission('file_list'),
            'file_remove' => $this->xpdo->hasPermission('file_remove'),
            'file_update' => $this->xpdo->hasPermission('file_update'),
            'file_upload' => $this->xpdo->hasPermission('file_upload'),
            'file_unpack' => $this->xpdo->hasPermission('file_unpack'),
            'file_view' => $this->xpdo->hasPermission('file_view'),
            'file_create' => $this->xpdo->hasPermission('file_create'),
        );
        return $this->permissions;
    }

    /**
     * See if the source is allowing a certain permission.
     *
     * @param string $key
     * @return bool
     */
    public function hasPermission($key) {
        return !empty($this->permissions[$key]);
    }

    /**
     * Add an error for an action occurring in the source
     *
     * @param string $field The field corresponding to the error
     * @param string $message The message to add
     * @return string The added error
     */
    public function addError($field,$message) {
        $this->errors[$field] = $message;
        return $message;
    }

    /**
     * Get all errors that have occurred for this source
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * See if the source has any errors
     * @return bool
     */
    public function hasErrors() {
        return !empty($this->errors);
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }


    /**
     * @return FilesystemInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }


    /**
     * Get base paths/urls and sanitize incoming paths
     *
     * @param string $path A path to the active directory
     * @return array
     */
    protected function getBases($path = '')
    {
        //@TODO review
        $properties = $this->getProperties();
        $bases = array();
        $path = $this->fileHandler->sanitizePath($path);
        $bases['path'] = $properties['basePath']['value'];
        $bases['pathIsRelative'] = false;
        if (!empty($properties['basePathRelative']['value'])) {
            $bases['pathAbsolute'] = realpath("{$this->ctx->getOption('base_path',MODX_BASE_PATH)}{$bases['path']}"). '/';
            $bases['pathIsRelative'] = true;
        } else {
            $bases['pathAbsolute'] = $bases['path'];
        }

        $bases['pathAbsoluteWithPath'] = $bases['pathAbsolute'].ltrim($path,'/');
        if (is_dir($bases['pathAbsoluteWithPath'])) {
            $bases['pathAbsoluteWithPath'] = $this->fileHandler->postfixSlash($bases['pathAbsoluteWithPath']);
        }
        $bases['pathRelative'] = ltrim($path,'/');

        /* get relative url */
        $bases['urlIsRelative'] = false;
        $bases['url'] = $properties['baseUrl']['value'];;
        if (!empty($properties['baseUrlRelative']['value'])) {
            $bases['urlAbsolute'] = $this->ctx->getOption('base_url',MODX_BASE_URL).$bases['url'];
            $bases['urlIsRelative'] = true;
        } else {
            $bases['urlAbsolute'] = $bases['url'];
        }

        $bases['urlAbsoluteWithPath'] = $bases['urlAbsolute'].ltrim($path,'/');
        $bases['urlRelative'] = ltrim($path,'/');
        return $bases;
    }

    /**
     * Get the ID of the edit file action
     *
     * @return boolean|int
     */
    protected function getEditActionId() {
        return 'system/file/edit';
    }

    /**
     * @return array
     */
    protected function getPropertyListWithDefaults()
    {
        $properties = $this->getPropertyList();

        $properties['use_multibyte'] = $this->getOption('use_multibyte', $properties, false);
        $properties['modx_charset'] = $this->getOption('modx_charset', $properties, 'UTF-8');
        $properties['hideFiles'] = !empty($properties['hideFiles']) && $properties['hideFiles'] != 'false' ? true : false;
        $properties['hideTooltips'] = !empty($properties['hideTooltips']) && $properties['hideTooltips'] != 'false' ? true : false;

        $properties['imageExtensions'] = $this->getOption('imageExtensions', $properties, 'jpg,jpeg,png,gif,svg');

        return $properties;
    }
    /**
     * Return an array of files and folders at this current level in the directory structure
     *
     * @param string $path
     * @return array
     */
    public function getContainerList($path)
    {
        $properties = $this->getPropertyListWithDefaults();
        // @TODO can this method be moved elsewhere?  to another class? is it even needed anymore?
        $path = $this->fileHandler->postfixSlash($path);
        if ($path == '/' || $path == '\\' || $path == DIRECTORY_SEPARATOR) {
            $path = '';
        }

        $bases = $this->getBases($path);
        $fullPath = $path;
        if (!empty($bases['pathAbsolute'])) {
            $fullPath = $bases['pathAbsolute'].ltrim($path,'/');
        }

        $imageExtensions = explode(',', $properties['imageExtensions']);
        $skipFiles = $this->getSkipFilesArray($properties);

        $allowedExtensions = $this->getAllowedExtensionsArray($properties);

        $directories = $dirNames = $files = $fileNames = [];

        if (!empty($path)) {
            try {
                $meta = $this->filesystem->getMetadata($path);

            } catch (League\Flysystem\FileNotFoundException $exception) {
                // @TODO error message to user, bad file path
                return [];
            }
            if (isset($meta['type']) && $meta['type'] != 'dir') {
                return [];
            }
        }

        /** @var array $contents */
        $contents = $this->filesystem->listContents($path);
        foreach ($contents as $object) {
            if (in_array($object['path'], $skipFiles) || in_array(trim($object['path'],'/'), $skipFiles) || (in_array($fullPath.$object['path'], $skipFiles)) ) {
                continue;
            }
            $file_name = basename($object['path']);

            if ($object['type'] == 'dir' && $this->hasPermission('directory_list')) {
                $cls = $this->getExtJSDirClasses();
                $dirNames[] = strtoupper($file_name);
                $directories[$file_name] = array(
                    'id' => rtrim($object['path'],'/').'/',// $bases['urlRelative'].
                    'sid' => $this->get('id'),
                    'text' => $file_name,
                    'cls' => implode(' ',$cls),
                    'iconCls' => 'icon icon-folder',
                    'type' => 'dir',
                    'leaf' => false,
                    'path' => $object['path'],// $bases['pathAbsolute'].
                    'pathRelative' => $object['path'], // $bases['pathRelative'].
                    'perms' => $this->getOctalPerms($object['path']),
                    // visibility is only for files:
                    'visibility' => 'public',//$this->filesystem->getVisibility($object['path']),
                    'menu' => array(),
                );
                $directories[$file_name]['menu'] = array(
                    'items' => $this->getListDirContextMenu()
                );

            } elseif ($object['type'] == 'file' && !$properties['hideFiles'] && $this->hasPermission('file_list')) {
                // @TODO review/refactor extension and mime_type would be better for filesystems that may not always have an extension on it
                // example would be S3 and you have an HTML file but the name is just myPage
                //$this->filesystem->getMimetype($object['path']);
                $ext = pathinfo($object['path'], PATHINFO_EXTENSION);
                $ext = $properties['use_multibyte'] ? mb_strtolower($ext, $properties['modx_charset']) : strtolower($ext);
                if (!empty($allowedExtensions) && !in_array($ext, $allowedExtensions)) {
                    continue;
                }
                $fileNames[] = strtoupper($file_name);
                $files[$file_name] = $this->buildFileList($object['path'], $ext, $imageExtensions, $bases, $properties);
            }
        }

        $ls = array();
        /* now sort files/directories */
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
     * @return array
     */
    public function getObjectsInContainer($path)
    {
        $properties = $this->getPropertyListWithDefaults();
        // @TODO can this method be moved elsewhere?  to another class?
        $path = $this->fileHandler->postfixSlash($path);
        $bases = $this->getBases($path);

        $fullPath = $path;
        if (!empty($bases['pathAbsolute'])) {
            $fullPath = $bases['pathAbsolute'].ltrim($path,'/');
        }

        $imageExtensions = explode(',', $properties['imageExtensions']);
        $skipFiles = $this->getSkipFilesArray($properties);

        $allowedExtensions = $this->getAllowedExtensionsArray($properties);

        $files = $fileNames = [];

        $meta = $this->filesystem->getMetadata($path);
        if (isset($meta['type']) && $meta['type'] != 'dir') {
            return [];
        }

        /** @var array $contents */
        $contents = $this->filesystem->listContents($path);
        foreach ($contents as $object) {
            if (in_array($object['path'], $skipFiles) || in_array(trim($object['path'],'/'), $skipFiles) || (in_array($fullPath.$object['path'], $skipFiles)) ) {
                continue;
            }

            if ($object['type'] == 'dir' && $this->hasPermission('directory_list')) {
                continue;

            } elseif ($object['type'] == 'file' && !$properties['hideFiles'] && $this->hasPermission('file_list')) {
                // @TODO review/refactor ext and mime_type would be better for filesystems that may not always have an extension on it
                // example would be S3 and you have an HTML file but the name is just myPage
                //$this->filesystem->getMimetype($object['path']);
                $ext = pathinfo($object['path'], PATHINFO_EXTENSION);
                $ext = $properties['use_multibyte'] ? mb_strtolower($ext, $properties['modx_charset']) : strtolower($ext);
                if (!empty($allowedExtensions) && !in_array($ext, $allowedExtensions)) {
                    continue;
                }
                $fileNames[] = strtoupper($object['path']);

                $files[$object['path']] = $this->buildFileBrowserViewList($object['path'], $ext, $imageExtensions, $bases, $properties);
            }
        }

        $ls = array();
        /* now sort files/directories */
        array_multisort($fileNames, SORT_ASC, SORT_STRING, $files);
        foreach ($files as $file) {
            $ls[] = $file;
        }

        return $ls;
    }


    /**
     * Get the contents of a specified file
     *
     * @param string $path
     *
     * @return array
     */
    public function getObjectContents($path)
    {
        $properties = $this->getPropertyList();
        $path = $this->fileHandler->postfixSlash($path);

        if (!$this->filesystem->has($path)) {
            $this->addError('file',$this->xpdo->lexicon('file_err_nf'));
        }

        try {
            /** @var League\Flysystem\File $file */
            $file = $this->filesystem->get($path);

        } catch (League\Flysystem\FileNotFoundException $exception) {
            $this->addError('path',$this->xpdo->lexicon('file_err_nf'));
            // @TODO review the return
            return [];
        }

        if ($file->getType() !== 'file' ) {
            $this->addError('file', $this->xpdo->lexicon('file_err_nf'));
            // @TODO review the return
            return [];
        }

        $imageExtensions = $this->getOption('imageExtensions', $properties, 'jpg,jpeg,png,gif,svg');
        $imageExtensions = explode(',', $imageExtensions);
        $fileExtension = pathinfo($path, PATHINFO_EXTENSION);

        $fa = array(
            'name' => rtrim($path, '/'),
            'basename' => basename($file->getPath()),
            'path' => $file->getPath(),
            'size' => $file->getSize(),
            'last_accessed' => $file->getTimestamp(),
            'last_modified' => $file->getTimestamp(),
            'content' => $file->read(),
            'visibility' => $file->getVisibility(),
            'image' => in_array($fileExtension, $imageExtensions) ? true : false,
            'is_writable' => true,//$file->isWritable(), @TODO review
            'is_readable' => true,//$file->isReadable(),
        );
        return $fa;
    }

    /**
     * Create a filesystem folder
     *
     * @param string $name
     * @param string $parentContainer
     * @return boolean
     */
    public function createContainer($name, $parentContainer)
    {
        if ($parentContainer == '/') {
            $parentContainer = '';
        }
        $path = $this->fileHandler->sanitizePath($parentContainer.'/'.ltrim($name, '/'));

        if ($this->filesystem->has($path)) {
            $this->addError('name', $this->xpdo->lexicon('file_folder_err_ae'));
            return false;
        }

        if (!$this->filesystem->createDir($path)) {
            $this->addError('name', $this->xpdo->lexicon('file_folder_err_create'));
            return false;
        }
        /** @var League\Flysystem\Directory $dirObject */
        $dirObject = $this->filesystem->get($path);

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerDirCreate',array(
            'directory' => $dirObject->getPath(),
            'source' => &$this,
        ));

        $this->xpdo->logManagerAction('directory_create','', $dirObject->getPath());

        return true;
    }

    /**
     * Create a file
     *
     * @param string $path
     * @param string $name
     * @param string $content
     * @return boolean|string
     */
    public function createObject($path, $name, $content)
    {
        if ($path == '/') {
            $path = '';
        }
        $path = $this->fileHandler->sanitizePath($path.'/'.ltrim($name, '/'));

        if (!$this->checkFileType($path)) {
            // @TODO what should the error be?
            $this->addError('path',$this->xpdo->lexicon('file_folder_err_invalid'));
            return false;
        }

        if ($this->filesystem->has($path)) {
            $this->addError('name', $this->xpdo->lexicon('file_folder_err_ae'));
            return false;
        }

        if (!$this->filesystem->put($path, $content)) {
            $this->addError('name', $this->xpdo->lexicon('file_folder_err_create'));
            return false;
        }
        /** @var League\Flysystem\File $fileObject */
        $fileObject = $this->filesystem->get($path);

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerFileCreate',array(
            'path' => $fileObject->getPath(),
            'source' => &$this,
        ));

        $this->xpdo->logManagerAction('file_create','', $fileObject->getPath());

        return rawurlencode($fileObject->getPath());
    }

    /**
     * Move a file or folder to a specific location
     *
     * @param string $from The location to move from
     * @param string $to The location to move to
     * @param string $point @TODO what is this for?
     * @param int $to_source
     *
     * @return boolean
     */
    public function moveObject($from, $to, $point = 'append', $to_source=0)
    {
        $path = $this->fileHandler->postfixSlash($from);
        $to = $this->fileHandler->postfixSlash($to);
        $newPath = rtrim($to,'/').'/'.basename($path);

        $success = false;

        /* verify source path */
        try {
            /** @var League\Flysystem\Directory|League\Flysystem\File $originalObject */
            $originalObject = $this->filesystem->get($path);

        } catch (League\Flysystem\FileNotFoundException $exception) {
            $this->addError('path',$this->xpdo->lexicon('file_err_nf'));
            return false;
        }

        if ($to_source) {
            $this->xpdo->loadClass('sources.modMediaSource');
            /** @var modMediaSource $toSource */
            $toSource = modMediaSource::getDefaultSource($this->xpdo, $to_source);
            if (!$toSource->getWorkingContext()) {
                $this->addError('source', $this->xpdo->lexicon('permission_denied'));
                return false;
            }

            $toSource->initialize();
            if (!$toSource->checkPolicy('save')) {
                $this->addError('source', $this->xpdo->lexicon('permission_denied'));
                return false;
            }

            /** @var League\Flysystem\Filesystem $destination */
            $destination = $toSource->getFilesystem();

            /** @var League\Flysystem\MountManager $mountManager */
            $mountManager = new League\Flysystem\MountManager([
                'org' => $this->filesystem,
                'destination' => $destination,
            ]);

            $success = $mountManager->move(
                'org://'.ltrim($path, '/'),
                'destination://'.ltrim($newPath, '/')
            );


            /** @var League\Flysystem\Directory|League\Flysystem\File $movedObject */
            $movedObject = $destination->get($newPath);

        } else {
            /** @TODO review
             * if (!$fromObject->isReadable() || !$fromObject->isWritable()) {
             * $this->addError('from',$this->xpdo->lexicon('file_err_nf').': '.$fromPath);
             * return $success;
             * }
             */
            $toSource = false;

            if (!$this->filesystem->rename($path, $newPath)) {
                $this->addError('from', $this->xpdo->lexicon('file_err_rename'));
                return false;
            }

            /** @var League\Flysystem\Directory|League\Flysystem\File $movedObject */
            $movedObject = $this->filesystem->get($newPath);
        }

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerMoveObject',array(
            'from' => $originalObject->getPath(),
            'to' => $movedObject->getPath(),
            'source' => $this,
            'toSource' => $toSource
        ));

        return $success;
    }
    /**
     * Remove a folder at the specified location
     *
     * @param string $path ~ pre 3.0 $path was full absolute path, all paths need to be relative
     * @return boolean
     */
    public function removeContainer($path)
    {
        $path = $this->fileHandler->postfixSlash($path);

        /** @var League\Flysystem\Directory $dirObject */
        try {
            $dirObject = $this->filesystem->get($path);

        } catch (League\Flysystem\FileNotFoundException $exception) {
            $this->addError('path',$this->xpdo->lexicon('file_folder_err_invalid'));
            return false;
        }

        if (!$dirObject instanceof  League\Flysystem\Directory) {
            $this->addError('path',$this->xpdo->lexicon('file_folder_err_invalid'));
            return false;
        }
        $full_path = $dirObject->getPath();

        if (!$this->filesystem->deleteDir($path)) {
            $this->addError('path',$this->xpdo->lexicon('file_folder_err_remove'));
            return false;
        }

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerDirRemove',array(
            'directory' => $full_path,
            'source' => &$this,
        ));

        $this->xpdo->logManagerAction('directory_remove','', $full_path);
        return true;
    }

    /**
     * Remove a file
     *
     * @param string $path
     * @return boolean
     */
    public function removeObject($path)
    {
        $path = $this->fileHandler->postfixSlash($path);

        if (!$this->filesystem->has($path)) {
            $this->addError('file', $this->xpdo->lexicon('file_err_nf').': '.$path);
            return false;
        }

        /** @var League\Flysystem\File $fileObject */
        $fileObject = $this->filesystem->get($path);
        $full_path = $fileObject->getPath();

        if (!$this->filesystem->delete($path)) {
            $this->addError('file', $this->xpdo->lexicon('file_err_remove'));
            return false;
        }

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerFileRemove',array(
            'directory' => $full_path,
            'source' => &$this,
        ));

        $this->xpdo->logManagerAction('file_remove','', $full_path);
        return true;
    }

    /**
     * @param string $oldPath
     * @param string $newName
     * @return bool
     */
    public function renameContainer($oldPath, $newName)
    {
        $oldPath = $this->fileHandler->postfixSlash($oldPath);

        /* make sure is a directory and writable */
        if (!$this->filesystem->has($oldPath)) {
            $this->addError('name',$this->xpdo->lexicon('file_folder_err_invalid'));
            return false;
        }

        /** @var League\Flysystem\Directory $dir_object */
        $dirObject = $this->filesystem->get($oldPath);
        $full_path = $dirObject->getPath();

        /* sanitize new path */
        $newPath = $this->fileHandler->sanitizePath($newName);
        $newPath = $this->fileHandler->postfixSlash($newPath);
        $newPath = dirname($oldPath).'/'.$newPath;

        /* check to see if the new resource already exists */
        if ($this->filesystem->has($newPath)) {
            $this->addError('name', $this->xpdo->lexicon('file_folder_err_ae'));
            return false;
        }

        /* rename the dir */
        try {
            // @TODO S3 needs some work for just directories and does not look like adapters rename directories?
            if (!$this->filesystem->copy($oldPath, $newPath)) {
                $this->addError('name', $this->xpdo->lexicon('file_folder_err_rename'));
                return false;
            }
            $this->filesystem->deleteDir($oldPath);
        } catch (Exception $exception) {
            $this->addError('name', $this->xpdo->lexicon('file_folder_err_rename').' E: '.$exception->getMessage());
            return false;
        }

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerDirRename',array(
            'directory' => $newPath,
            'source' => &$this,
        ));

        $this->xpdo->logManagerAction('directory_rename', '', $full_path);
        return true;
    }

    /**
     * @param string $oldPath
     * @param string $newName
     * @return bool
     */
    public function renameObject($oldPath, $newName)
    {
        $oldPath = $this->fileHandler->postfixSlash($oldPath);

        /* make sure is a directory and writable */
        if (!$this->filesystem->has($oldPath)) {
            $this->addError('name', $this->xpdo->lexicon('file_err_invalid'));
            return false;
        }

        /** @var League\Flysystem\Directory $dir_object */
        $fileObject = $this->filesystem->get($oldPath);
        $full_path = $fileObject->getPath();

        /* sanitize new path */
        $newPath = $this->fileHandler->sanitizePath($newName);
        $newPath = $this->fileHandler->postfixSlash($newPath);
        $newPath = dirname($oldPath).'/'.$newPath;

        /* check to see if the new resource already exists */
        if ($this->filesystem->has($newPath)) {
            $this->addError('name', sprintf($this->xpdo->lexicon('file_err_ae'), $newName));
            return false;
        }

        if (!$this->checkFileType($newName)) {
            // @TODO what should the error message be?
            $this->addError('name', $this->xpdo->lexicon('file_folder_err_ae'));
            return false;
        }

        /* rename the dir */
        if (!$this->filesystem->rename($oldPath, $newPath)) {
            $this->addError('name', $this->xpdo->lexicon('file_folder_err_rename'));
            return false;
        }

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerFileRename',array(
            'path' => $newPath,
            'source' => &$this,
        ));

        $this->xpdo->logManagerAction('file_rename','', $full_path);
        return true;
    }


    public function updateContainer()
    {
        // @TODO never used in MODX? Kill it?
        return true;
    }

    /**
     * Update the contents of a file
     *
     * @param string $path
     * @param string $content
     *
     * @return boolean|string
     */
    public function updateObject($path, $content)
    {
        $path = $this->fileHandler->sanitizePath($path);

        if (!$this->checkFileType($path)) {
            // @TODO what should the error be?
            $this->addError('path',$this->xpdo->lexicon('file_folder_err_invalid'));
            return false;
        }

        if (!$this->filesystem->has($path)) {
            $this->addError('file', $this->xpdo->lexicon('file_err_nf') . ': '.$path);
            return false;
        }

        if (!$this->filesystem->update($path, $content)) {
            $this->addError('name', $this->xpdo->lexicon('file_folder_err_update'));
            return false;
        }
        /** @var League\Flysystem\File $fileObject */
        $fileObject = $this->filesystem->get($path);

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerFileUpdate',array(
            'path' => $fileObject->getPath(),
            'source' => &$this,
        ));

        $this->xpdo->logManagerAction('file_update','', $fileObject->getPath());

        return rawurlencode($fileObject->getPath());
    }

    /**
     * Upload files to a specific folder on the file system
     *
     * @param string $container
     * @param array $objects
     * @return boolean
     */
    public function uploadObjectsToContainer($container, array $objects = array())
    {
        $container = $this->fileHandler->postfixSlash($container);

        /** @var array $properties */
        $properties = $this->getPropertyList();

        $visibility = $this->xpdo->getOption('visibility', $properties, League\Flysystem\Adapter\AbstractAdapter::VISIBILITY_PUBLIC);

        /** @var League\Flysystem\Directory $fileObject */
        $dirObject = $this->filesystem->get($container);
        if (!$dirObject instanceof  League\Flysystem\Directory) {
            $this->addError('path',$this->xpdo->lexicon('file_folder_err_invalid').': '.$container);
            return false;
        }

        /** @TODO review I don't think this can be repeated in Flysystem:
        if (!($directory->isReadable()) || !$directory->isWritable()) {
            $this->addError('path',$this->xpdo->lexicon('file_folder_err_perms_upload').': '.$fullPath);
            return false;
        }
        */

        $this->xpdo->context->prepare();

        $maxFileSize = $this->xpdo->getOption('upload_maxsize',null,1048576);

        // @TODO this is now visibility, remove or deprecate permission?
        $mode = $this->fileHandler->modx->getOption('new_file_permissions');
        if ($mode) {
            $mode = octdec($mode);
        }

        /* loop through each file and upload */
        foreach ($objects as $file) {
            /* invoke event */
            $this->xpdo->invokeEvent('OnFileManagerBeforeUpload', array(
                'files' => &$objects,
                'file' => &$file,
                'directory' => $container,
                'source' => &$this,
            ));

            if ($file['error'] != 0 || empty($file['name']) || !$this->checkFileType($file['name'])) {
                // @TODO review addError?
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

            $newPath = $container.$this->fileHandler->sanitizePath($file['name']);

            if (!$this->filesystem->put($newPath, file_get_contents($file['tmp_name'])) ) {
                $this->addError('path', $this->xpdo->lexicon('file_err_upload'));
                continue;
            }

            $this->setVisibility($newPath, $visibility);
        }

        /* invoke event */
        $this->xpdo->invokeEvent('OnFileManagerUpload',array(
            'files' => &$objects,
            'directory' => $container,
            'source' => &$this,
        ));

        $this->xpdo->logManagerAction('file_upload', '', $dirObject->getPath());

        return !$this->hasErrors();
    }

    /**
     * @param string $path ~ relative path of file/directory
     * @param string $visibility ~ public or private
     *
     * @return bool
     */
    public function setVisibility($path, $visibility)
    {
        $path = $this->fileHandler->sanitizePath($path);

        if ($visibility != \League\Flysystem\Adapter\AbstractAdapter::VISIBILITY_PUBLIC
            && $visibility != \League\Flysystem\Adapter\AbstractAdapter::VISIBILITY_PRIVATE) {
            // invalid
            $this->addError('file', $this->xpdo->lexicon('file_err_nf') . ': '.$path.' '.$visibility);
            return false;
        };

        try {
            $saved = $this->filesystem->setVisibility($path, $visibility);

        } catch (League\Flysystem\FileNotFoundException $exception) {
            $this->addError('path',$this->xpdo->lexicon('file_err_nf'));
            $saved = false;
        }

        return $saved;
    }

    /************
     * the following need to be completed in child classes
     ***********/
    public function getBasePath($object = '') { return ''; }
    public function getBaseUrl($object = '') { return ''; }
    public function getObjectUrl($object = '') { return ''; }
    public function getDefaultProperties() { return array(); }


    /**
     * Get the openTo directory for this source, used with TV input types
     *
     * @param string $value
     * @param array $parameters
     * @return string
     */
    public function getOpenTo($value,array $parameters = array()) {
        $dirname = dirname($value);
        return $dirname == '.' ? '' : $dirname . '/';
    }

    /**
     * Get the name of this source type
     * @return string
     */
    public function getTypeName() {
        $this->xpdo->lexicon->load('source');
        return $this->xpdo->lexicon('source_type.file');
    }
    /**
     * Get the description of this source type
     * @return string
     */
    public function getTypeDescription() {
        $this->xpdo->lexicon->load('source');
        return $this->xpdo->lexicon('source_type.file_desc');
    }

    /**
     * Get the properties on this source
     * @param boolean $parsed
     * @return array
     */
    public function getProperties($parsed = false) {
        $properties = $this->get('properties');
        $defaultProperties = $this->getDefaultProperties();
        if (!empty($properties) && is_array($properties)) {
            foreach ($properties as &$property) {
                $property['overridden'] = 0;
                if (array_key_exists($property['name'],$defaultProperties)) {
                    if ($defaultProperties[$property['name']]['value'] != $property['value']) {
                        $property['overridden'] = 1;
                    }
                } else {
                    $property['overridden'] = 2;
                }
            }
            $properties = array_merge($defaultProperties,$properties);
        } else {
            $properties = $defaultProperties;
        }
        /** @var array $results Allow manipulation of media source properties via event */
        $results = $this->xpdo->invokeEvent('OnMediaSourceGetProperties',array(
            'properties' => $this->xpdo->toJSON($properties),
        ));
        if (!empty($results)) {
            foreach ($results as $result) {
                $result = is_array($result) ? $result : $this->xpdo->fromJSON($result);
                if (!is_array($result)) {
                    $result = array();
                }
                $properties = array_merge($properties,$result);
            }
        }
        if ($parsed) {
            $properties = $this->parseProperties($properties);
        }
        return $this->prepareProperties($properties);
    }

    /**
     * Get all properties in key => value format
     * @return array
     */
    public function getPropertyList() {
        $properties = $this->getProperties(true);
        $list = array();
        foreach ($properties as $property) {
            $value = $property['value'];
            if (!empty($property['xtype']) && $property['xtype'] == 'combo-boolean') {
                $value = empty($property['value']) && $property['value'] != 'true' ? false : true;
            }
            $list[$property['name']] = $value;
        }
        $list = array_merge($list,$this->properties);
        return $list;
    }

    /**
     * Parse any tags in the properties
     * @param array $properties
     * @return array
     */
    public function parseProperties(array $properties) {
        $properties = $this->getProperties();
        $this->xpdo->getParser();
        if ($this->xpdo->parser) {
            foreach ($properties as &$property) {
                $this->xpdo->parser->processElementTags('',$property['value'],true,true);
            }
        }
        return $properties;
    }

    /**
     * Translate any needed properties
     * @param array $properties
     * @return array
     */
    public function prepareProperties(array $properties = array()) {
        foreach ($properties as &$property) {
            if (!empty($property['lexicon'])) {
                $this->xpdo->lexicon->load($property['lexicon']);
            }
            if (!empty($property['name'])) {
                $property['name_trans'] = $this->xpdo->lexicon($property['name']);
            }
            if (!empty($property['desc'])) {
                $property['desc_trans'] = $this->xpdo->lexicon($property['desc']);
            }
            if (!empty($property['options'])) {
                foreach ($property['options'] as &$option) {
                    if (empty($option['text']) && !empty($option['name'])) {
                        $option['text'] = $option['name'];
                        unset($option['name']);
                    }
                    if (empty($option['value']) && !empty($option[0])) {
                        $option['value'] = $option[0];
                        unset($option[0]);
                    }
                    $option['name'] = $this->xpdo->lexicon($option['text']);
                }
            }
        }
        return $properties;
    }

    /**
     * Set the properties for this Source
     *
     * @param array $properties
     * @param boolean $merge
     * @return bool
     */
    public function setProperties($properties, $merge = false) {
        $default = $this->getDefaultProperties();

        foreach ($properties as $k => $prop) {
            if (is_array($prop) && array_key_exists($prop['name'],$default)) {
                if ($prop['value'] == $default[$prop['name']]['value']) {
                    unset($properties[$k]);
                }
            } else if (is_scalar($prop)) { /* properties is k=>v pair */
                if ($prop == $default[$k]['value']) {
                    unset($properties[$k]);
                }
            }
        }

        $set = false;
        $propertiesArray = array();
        if (is_string($properties)) {
            $properties = $this->xpdo->parser->parsePropertyString($properties);
        }
        if (is_array($properties)) {
            foreach ($properties as $propKey => $property) {
                if (is_array($property) && isset($property[5])) {
                    $key = $property[0];
                    $propertyArray = array(
                        'name' => $property[0],
                        'desc' => $property[1],
                        'type' => $property[2],
                        'options' => $property[3],
                        'value' => $property[4],
                        'lexicon' => !empty($property[5]) ? $property[5] : null,
                    );
                } elseif (is_array($property) && isset($property['value'])) {
                    $key = $property['name'];
                    $propertyArray = array(
                        'name' => $property['name'],
                        'desc' => isset($property['description']) ? $property['description'] : (isset($property['desc']) ? $property['desc'] : ''),
                        'type' => isset($property['xtype']) ? $property['xtype'] : (isset($property['type']) ? $property['type'] : 'textfield'),
                        'options' => isset($property['options']) ? $property['options'] : array(),
                        'value' => $property['value'],
                        'lexicon' => !empty($property['lexicon']) ? $property['lexicon'] : null,
                    );
                } else {
                    $key = $propKey;
                    $propertyArray = array(
                        'name' => $propKey,
                        'desc' => '',
                        'type' => 'textfield',
                        'options' => array(),
                        'value' => $property,
                        'lexicon' => null,
                    );
                }

                if (!empty($propertyArray['options'])) {
                    foreach ($propertyArray['options'] as $optionKey => &$option) {
                        if (empty($option['text']) && !empty($option['name'])) $option['text'] = $option['name'];
                        unset($option['menu'],$option['name']);
                    }
                }

                if ($propertyArray['type'] == 'combo-boolean' && is_numeric($propertyArray['value'])) {
                    $propertyArray['value'] = (boolean)$propertyArray['value'];
                }

                $propertiesArray[$key] = $propertyArray;
            }

            if ($merge && !empty($propertiesArray)) {
                $existing = $this->get('properties');
                if (is_array($existing) && !empty($existing)) {
                    $propertiesArray = array_merge($existing, $propertiesArray);
                }
            }
            $set = $this->set('properties', $propertiesArray);
        }
        return $set;
    }

    /**
     * Prepare the source path for phpThumb
     *
     * @param string $src
     * @return string
     */
    public function prepareSrcForThumb($src) {

        /** @var League\Flysystem\File $dirObject */
        try {
            $fileObject = $this->filesystem->get($src);

        } catch (League\Flysystem\FileNotFoundException $exception) {
            return '';

        }

        $src = $fileObject->getPath();
        $properties = $this->getPropertyList();

        if ($properties['url']) {
            $src = $properties['url'].'/'.ltrim($src, '/');
        }

        /* dont strip stuff for absolute URLs */

        if (substr($src,0,4) != 'http') {
            if (strpos($src,'/') !== 0) {
                $src = !empty($properties['basePath']) ? $properties['basePath'].$src : $src;
                if (!empty($properties['basePathRelative'])) {
                    $src = $this->ctx->getOption('base_path',null,MODX_BASE_PATH).$src;
                }
            }
            /* strip out double slashes */
            $src = str_replace(array('///','//'),'/',$src);

            /* check for file existence if local url */
            if (strpos($src,'/') !== 0 && empty($src)) {
                if (file_exists('/'.$src)) {
                    $src = '/'.$src;
                } else {
                    return '';
                }
            }
        }
        return $src;
    }

    /**
     * Prepares the output URL when the Source is being used in an Element. Can be overridden to provide prefixing/post-
     * fixing functionality.
     *
     * @param string $value
     * @return string
     */
    public function prepareOutputUrl($value) {
        return $value;
    }

    /**
     * Find all policies for this object
     *
     * @param string $context
     * @return array
     */
    public function findPolicy($context = '') {
        $policy = array();
        $enabled = true;
        $context = 'mgr';
        if ($context === $this->xpdo->context->get('key')) {
            $enabled = (boolean) $this->xpdo->getOption('access_media_source_enabled', null, true);
        } elseif ($this->xpdo->getContext($context)) {
            $enabled = (boolean) $this->xpdo->contexts[$context]->getOption('access_media_source_enabled', true);
        }
        if ($enabled) {
            if (empty($this->_policies) || !isset($this->_policies[$context])) {
                $accessTable = $this->xpdo->getTableName('sources.modAccessMediaSource');
                $sourceTable = $this->xpdo->getTableName('sources.modMediaSource');
                $policyTable = $this->xpdo->getTableName('modAccessPolicy');
                $sql = "SELECT Acl.target, Acl.principal, Acl.authority, Acl.policy, Policy.data FROM {$accessTable} Acl " .
                        "LEFT JOIN {$policyTable} Policy ON Policy.id = Acl.policy " .
                        "JOIN {$sourceTable} Source ON Acl.principal_class = 'modUserGroup' " .
                        "AND (Acl.context_key = :context OR Acl.context_key IS NULL OR Acl.context_key = '') " .
                        "AND Source.id = Acl.target " .
                        "WHERE Acl.target = :source " .
                        "GROUP BY Acl.target, Acl.principal, Acl.authority, Acl.policy";
                $bindings = array(
                    ':source' => $this->get('id'),
                    ':context' => $context,
                );
                $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
                if ($query->stmt && $query->stmt->execute()) {
                    while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                        $policy['sources.modAccessMediaSource'][$row['target']][] = array(
                            'principal' => $row['principal'],
                            'authority' => $row['authority'],
                            'policy' => $row['data'] ? $this->xpdo->fromJSON($row['data'], true) : array(),
                        );
                    }
                }
                $this->_policies[$context] = $policy;
            } else {
                $policy = $this->_policies[$context];
            }
        }
        return $policy;
    }

    /**
     * Allow overriding of checkPolicy to always allow media sources to be loaded
     *
     * @param string|array $criteria
     * @param array $targets
     * @param modUser $user
     * @return bool
     */
    public function checkPolicy($criteria, $targets = null, modUser $user = null) {
        if ($criteria == 'load') {
            $success = true;
        } else {
            $success = parent::checkPolicy($criteria,$targets,$user);
        }
        return $success;
    }

    /**
     * Override xPDOObject::save to clear the sources cache on save
     *
     * @param boolean $cacheFlag
     * @return boolean
     */
    public function save($cacheFlag = null) {
        $saved = parent::save($cacheFlag);
        if ($saved) {
            $this->clearCache();
        }
        return $saved;
    }

    /**
     * Clear the caches of all sources
     * @param array $options
     * @return void
     */
    public function clearCache(array $options = array()) {
        /** @var modCacheManager $cacheManager */
        $cacheManager = $this->xpdo->getCacheManager();
        if (empty($cacheManager)) return;

        $c = $this->xpdo->newQuery('modContext');
        $c->select($this->xpdo->escape('key'));

        $options[xPDO::OPT_CACHE_KEY] = $this->getOption('cache_media_sources_key', $options, 'media_sources');
        $options[xPDO::OPT_CACHE_HANDLER] = $this->getOption('cache_media_sources_handler', $options, $this->getOption(xPDO::OPT_CACHE_HANDLER, $options));
        $options[xPDO::OPT_CACHE_FORMAT] = (integer) $this->getOption('cache_media_sources_format', $options, $this->getOption(xPDO::OPT_CACHE_FORMAT, $options, xPDOCacheManager::CACHE_PHP));
        $options[xPDO::OPT_CACHE_ATTEMPTS] = (integer) $this->getOption('cache_media_sources_attempts', $options, $this->getOption(xPDO::OPT_CACHE_ATTEMPTS, $options, 10));
        $options[xPDO::OPT_CACHE_ATTEMPT_DELAY] = (integer) $this->getOption('cache_media_sources_attempt_delay', $options, $this->getOption(xPDO::OPT_CACHE_ATTEMPT_DELAY, $options, 1000));

        if ($c->prepare() && $c->stmt->execute()) {
            while ($row = $c->stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row && !empty($row['key'])) {
                    $cacheManager->delete($row['key'].'/source',$options);
                }
            }
        }
    }

    /**
     * @param League\Flysystem\AdapterInterface $localAdapter
     * @param string $cache_type ~ memory, persistent or memcached
     */
    protected function loadFlySystem(League\Flysystem\AdapterInterface $localAdapter, $cache_type='memory')
    {
        /** @var League\Flysystem\Cached\CachedAdapter $cache */
        switch (strtolower($cache_type)) {
            case 'persistent':
                //no break
            case 'predis':
                // @TODO requires: composer require predis/predis
                $cache = new Predis();
                break;

            case 'memcached':
                $memcached = new \Memcached();
                // @TODO requires config data
                $memcached->addServer('localhost', 11211);

                $cache = new Memcached($memcached, 'storageKey', 300);
                break;

            case 'memory':
                // no break
            default:
                $cache = new CacheStore();
        }
        $this->adapter = new CachedAdapter($localAdapter, $cache);

        $this->filesystem = new Filesystem($this->adapter);
    }

    /**
     * Check that the filename has a file type extension that is allowed
     *
     * @param $filename
     * @return bool
     */
    protected function checkFileType($filename) {
        if ($this->getOption('allowedFileTypes')) {
            $allowedFileTypes = $this->getOption('allowedFileTypes');
            $allowedFileTypes = (!is_array($allowedFileTypes)) ? explode(',', $allowedFileTypes) : $allowedFileTypes;
        } else {
            $allowedFiles = $this->xpdo->getOption('upload_files') ? explode(',', $this->xpdo->getOption('upload_files')) : array();
            $allowedImages = $this->xpdo->getOption('upload_images') ? explode(',', $this->xpdo->getOption('upload_files')) : array();
            $allowedMedia = $this->xpdo->getOption('upload_media') ? explode(',', $this->xpdo->getOption('upload_media')) : array();
            $allowedFlash = $this->xpdo->getOption('upload_flash') ? explode(',', $this->xpdo->getOption('upload_flash')) : array();
            $allowedFileTypes = array_unique(array_merge($allowedFiles, $allowedImages, $allowedMedia, $allowedFlash));
            $this->setOption('allowedFileTypes', $allowedFileTypes);
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
     * @param string $path
     * @param string $ext
     * @param array $image_extensions
     * @param array $bases
     * @param array $properties
     *
     * @return array
     */
    protected function buildFileList($path, $ext, $image_extensions, $bases, $properties)
    {
        $file_name = basename($path);

        $editAction = $this->getEditActionId();
        $canSave = $this->checkPolicy('save');
        $canRemove = $this->checkPolicy('remove');

        $cls = [];

        $fullPath = $path;
        if (!empty($bases['pathAbsolute'])) {
            $fullPath = $bases['pathAbsolute'].ltrim($path,'/');
        }
        $encFile = rawurlencode($fullPath.$path);

        if (!empty($properties['currentFile']) && rawurldecode($properties['currentFile']) == $fullPath.$path && $properties['currentAction'] == $editAction) {
            $cls[] = 'active-node';
        }

        if ($this->hasPermission('file_remove') && $canRemove) {
            $cls[] = 'premove';
        }
        if ($this->hasPermission('file_update') && $canSave) {
            $cls[] = 'pupdate';
        }
        $page = null;

        if ($this->isFileBinary($path)) {
            $page = !empty($editAction) ? '?a=' . $editAction . '&file=' . $path . '&wctx=' . $this->ctx->get('key') . '&source=' . $this->get('id') : null;
        }
        $url = $bases['urlRelative'] . $path;

        $file_list = [
            'id' => $path,//$bases['urlRelative'].
            'sid' => $this->get('id'),
            'text' => $file_name,
            'cls' => implode(' ',$cls),
            'iconCls' => 'icon icon-file icon-'.$ext . ($this->isFileWritable($path) ? '' : ' icon-lock'),
            'type' => 'file',
            'leaf' => true,
            'page' => $page,
            'perms' => $this->getOctalPerms($path),
            'path' => $path, //$bases['pathAbsolute'].
            'pathRelative' => $path,
            'directory' => $bases['path'],
            'visibility' => $this->filesystem->getVisibility($path),
            'url' => $bases['url'].$path,
            'urlAbsolute' => $bases['urlAbsoluteWithPath'].ltrim($file_name,'/'),
            'file' => $encFile,
            'menu' => [
                'items' => $this->getListFileContextMenu($file_name, (empty($page) ? false : true))
            ],
        ];

        // trough tree config we can request a tree without image-preview tooltips, don't do any work if not necessary
        if (!$properties['hideTooltips']) {
            $file_list['qtip'] = '';
            // needs to be an image:
            if (in_array($ext, $image_extensions)) {

                $imageWidth = $this->ctx->getOption('filemanager_image_width', 400);
                $imageHeight = $this->ctx->getOption('filemanager_image_height', 300);

                $preview_image = $this->buildManagerImagePreview($path, $ext, $imageWidth, $imageHeight, $bases, $properties);
                $file_list['qtip'] = '<img src="'.$preview_image['src'].'" width="'.$preview_image['width'].'" height="'.$preview_image['height'].'" alt="'.$path.'" />';
            }
        }
        return $file_list;
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
        if ($this->isFileBinary($path)) {
            $page = !empty($editAction) ? '?a=' . $editAction . '&file=' . $path . '&wctx=' . $this->ctx->get('key') . '&source=' . $this->get('id') : null;
        }
        $url = $bases['urlRelative'] . $path;

        $actual_width = $width = $this->ctx->getOption('filemanager_image_width', 800);
        $actual_height = $height = $this->ctx->getOption('filemanager_image_height', 600);
        $preview_image_info = [
            'src' => $this->ctx->getOption('manager_url', MODX_MANAGER_URL).'templates/default/images/restyle/nopreview.jpg',
            'width' => $width,
            'height' => $height
        ];

        $thumb_width = $this->ctx->getOption('filemanager_thumb_width', 100);
        $thumb_height = $this->ctx->getOption('filemanager_thumb_height', 80);
        $thumb_image_info = [
            'src' => $this->ctx->getOption('manager_url', MODX_MANAGER_URL).'templates/default/images/restyle/nopreview.jpg',
            'width' => $thumb_width,
            'height' => $thumb_height
        ];

        $preview = 0;
        if (in_array($ext, $image_extensions)) {
            // build the image preview and the thumb
            $preview = 1;
            /** @var array $preview_image_info */
            $preview_image_info = $this->buildManagerImagePreview($path, $ext, $width, $height, $bases, $properties);

            /** @var array $preview_image_info */
            $thumb_image_info = $this->buildManagerImagePreview($path, $ext, $thumb_width, $thumb_height, $bases, $properties);

            if (isset($this->file_sizes[$path])) {
                $actual_width = $this->file_sizes[$path]['width'];
                $actual_height = $this->file_sizes[$path]['height'];
            }
        }

        /** @var League\Flysystem\File $file_object */
        $file_object = $this->filesystem->get($path);
        $file_object->getPath();
        $file_list = array(
            'id' => $path,//$bases['urlAbsoluteWithPath'].
            'sid' => $this->get('id'),
            'name' => $path,
            'cls' => 'icon-'.$ext,
            // preview
            'preview' => $preview,
            'image' => $preview_image_info['src'],
            'image_width' => $preview_image_info['width'],
            'image_height' => $preview_image_info['height'],
            // thumb
            'thumb' => $thumb_image_info['src'],
            'thumb_width' => $thumb_image_info['src'],
            'thumb_height' => $thumb_image_info['src'],

            //'url' => ltrim($path.$fileName,'/'),
            'url' => $bases['url'].$url,

            'relativeUrl' => ltrim($path,'/'),
            'fullRelativeUrl' => rtrim($bases['url']).ltrim($path,'/'),
            'ext' => $ext,
            'pathname' => $file_object->getPath(),
            'pathRelative' => $path,//$bases['pathRelative'].

            'lastmod' => $this->filesystem->getTimestamp($path),
            'visibility' => $this->filesystem->getVisibility($path),
            'disabled' => false,
            'perms' => $this->getOctalPerms($path),
            'leaf' => true,
            'page' => $page,
            'size' => $this->filesystem->getSize($path),
            'menu' => $this->getListFileContextMenu($path, (empty($page) ? false : true)),
        );

        return $file_list;
    }

    /**
     * @param array $properties
     *
     * @return array|mixed
     */
    protected function getSkipFilesArray($properties=[])
    {
        $skipFiles = $this->getOption('skipFiles',$properties,'.svn,.git,_notes,nbproject,.idea,.DS_Store');
        if ($this->xpdo->getParser()) {
            $this->xpdo->parser->processElementTags('',$skipFiles,true,true);
        }
        $skipFiles = explode(',',$skipFiles);
        $skipFiles[] = '.';
        $skipFiles[] = '..';
        return $skipFiles;
    }

    /**
     * @param array $properties
     *
     * @return array|mixed|string
     */
    protected function getAllowedExtensionsArray($properties=[])
    {
        $allowedExtensions = $this->getOption('allowedFileTypes', $properties, '');
        if (is_string($allowedExtensions)) {
            if (empty($allowedExtensions)) {
                $allowedExtensions = array();
            } else {
                $allowedExtensions = explode(',', $allowedExtensions);
            }
        }
        return $allowedExtensions;
    }

    /**
     * @return array
     */
    protected function getExtJSDirClasses()
    {

        $canSave = $this->checkPolicy('save');
        $canRemove = $this->checkPolicy('remove');
        $canCreate = $this->checkPolicy('create');

        $cls[] = 'folder';
        if ($this->hasPermission('directory_chmod') && $canSave) {
            $cls[] = 'pchmod';
        }
        if ($this->hasPermission('directory_create') && $canCreate) {
            $cls[] = 'pcreate';
        }
        if ($this->hasPermission('directory_remove') && $canRemove) {
            $cls[] = 'premove';
        }
        if ($this->hasPermission('directory_update') && $canSave) {
            $cls[] = 'pupdate';
        }
        if ($this->hasPermission('file_upload') && $canCreate) {
            $cls[] = 'pupload';
        }
        if ($this->hasPermission('file_create') && $canCreate) {
            $cls[] = 'pcreate';
        }
        return $cls;
    }
    /**
     * Get the context menu items for a specific dir object in the list view
     *
     * @return array
     */
    protected function getListDirContextMenu()
    {
        $canSave = $this->checkPolicy('save');
        $canRemove = $this->checkPolicy('remove');
        $canCreate = $this->checkPolicy('create');

        $menu = array();
        if ($this->hasPermission('directory_create') && $canCreate) {
            $menu[] = array(
                'text' => $this->xpdo->lexicon('file_folder_create_here'),
                'handler' => 'this.createDirectory',
            );
        }
        if ($this->hasPermission('directory_chmod') && $canSave) {
            if ($this->use_chmod) {
                // @deprecated
                $menu[] = array(
                    'text' => $this->xpdo->lexicon('file_folder_chmod'),
                    'handler' => 'this.chmodDirectory',
                );
            }
        }
        if ($this->hasPermission('directory_update') && $canSave) {
            $menu[] = array(
                'text' => $this->xpdo->lexicon('rename'),
                'handler' => 'this.renameDirectory',
            );
        }
        $menu[] = array(
            'text' => $this->xpdo->lexicon('directory_refresh'),
            'handler' => 'this.refreshActiveNode',
        );
        if ($this->hasPermission('file_upload') && $canCreate) {
            $menu[] = '-';
            $menu[] = array(
                'text' => $this->xpdo->lexicon('upload_files'),
                'handler' => 'this.uploadFiles',
            );
        }
        if ($this->hasPermission('file_create') && $canCreate) {
            $menu[] = array(
                'text' => $this->xpdo->lexicon('file_create'),
                'handler' => 'this.createFile',
            );
            $menu[] = array(
                'text' => $this->xpdo->lexicon('quick_create_file'),
                'handler' => 'this.quickCreateFile',
            );
        }
        if ($this->hasPermission('directory_remove') && $canRemove) {
            $menu[] = '-';
            $menu[] = array(
                'text' => $this->xpdo->lexicon('file_folder_remove'),
                'handler' => 'this.removeDirectory',
            );
        }

        return $menu;
    }
    /**
     * Get the context menu items for a specific file object in the list view
     *
     * @param string $file_name
     * @param bool $editable
     * @return array
     */
    protected function getListFileContextMenu($file_name, $editable=true) {
        $canSave = $this->checkPolicy('save');
        $canRemove = $this->checkPolicy('remove');
        $canView = $this->checkPolicy('view');

        $menu = [];
        if ($this->hasPermission('file_update') && $canSave) {
            if ($editable) {
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

            $menu[] = array(
                'text' => $this->xpdo->lexicon('file_folder_visibility'),
                'handler' => 'this.setVisibility',
            );
            $menu[] = '-';
        }
        if ($this->hasPermission('file_view') && $canView) {
            $menu[] = array(
                'text' => $this->xpdo->lexicon('file_download'),
                'handler' => 'this.downloadFile',
            );
        }
        if ($this->hasPermission('file_unpack') && $canView && pathinfo($file_name, PATHINFO_EXTENSION) === 'zip') {
            $menu[] = array(
                'text' => $this->xpdo->lexicon('file_download_unzip'),
                'handler' => 'this.unpackFile',
            );
        }
        if ($this->hasPermission('file_remove') && $canRemove) {
            if (!empty($menu)) $menu[] = '-';
            $menu[] = array(
                'text' => $this->xpdo->lexicon('file_remove'),
                'handler' => 'this.removeFile',
            );
        }

        return $menu;
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
    protected function buildManagerImagePreview($path, $ext, $width, $height, $bases, $properties=[])
    {
        $modAuth = $this->xpdo->user->getUserToken($this->xpdo->context->get('key'));

        $thumbnailType = $this->getOption('thumbnailType', $properties, 'png');
        $thumbnailQuality = $this->getOption('thumbnailQuality', $properties, 90);

        if ($ext == 'svg') {
            if (isset($this->file_sizes[$path])) {
                $file_size = $this->file_sizes[$path];
                if (is_array($file_size)) {
                    $width = $file_size['width'];
                    $height = $file_size['height'];
                }

            } else {
                $svgString = $this->filesystem->read($path);
                preg_match('/(<svg[^>]*\swidth=")([\d\.]+)([a-z]*)"/si', $svgString, $svgWidth);
                preg_match('/(<svg[^>]*\sheight=")([\d\.]+)([a-z]*)"/si', $svgString, $svgHeight);
                preg_match('/(<svg[^>]*\sviewBox=")([\d\.]+(?:,|\s)[\d\.]+(?:,|\s)([\d\.]+)(?:,|\s)([\d\.]+))"/si', $svgString, $svgViewbox);

                if (!empty($svgViewbox)) {
                    // get width and height from viewbox attribute
                    $width = round($svgViewbox[3]);
                    $height = round($svgViewbox[4]);

                } elseif (!empty($svgWidth) && !empty($svgHeight)) {
                    // get width and height from width and height attributes
                    $width = round($svgWidth[2]);
                    $height = round($svgHeight[2]);

                }

                $this->file_sizes[$path] = [
                    'width' => $width,
                    'height' => $height
                ];
            }

            $image = $bases['urlAbsolute'] . urldecode($bases['urlRelative'] . $path);

        } else {
            $imageQueryHeight = $height;
            $imageQueryWidth = $width;

            $size = $this->getImageDimensions($path);
            if (is_array($size) && $size['width'] > 0 && $size['height'] > 0) {
                // get original image size for proportional scaling
                if ($size['width'] > $size['height']) {
                    // landscape
                    $imageQueryWidth = $size['width'] >= $width ? $width : $size['width'];
                    $imageQueryHeight = 0;
                    $width = $imageQueryWidth;
                    $height = round($size['height'] * ($imageQueryWidth / $size['width']));
                } else {
                    // portrait or square
                    $imageQueryWidth = 0;
                    $imageQueryHeight = $size['height'] >= $height ? $height : $size['height'];
                    $width = round($size['width'] * ($imageQueryHeight / $size['height']));
                    $height = $imageQueryHeight;
                }

                $imageQuery = http_build_query(array(
                    'src' => $path,//$bases['urlRelative'].
                    'w' => $imageQueryWidth,
                    'h' => $imageQueryHeight,
                    'HTTP_MODAUTH' => $modAuth,
                    'f' => $thumbnailType,
                    'q' => $thumbnailQuality,
                    'wctx' => $this->ctx->get('key'),
                    'source' => $this->get('id'),
                ));
                $image = $this->ctx->getOption('connectors_url', MODX_CONNECTORS_URL) . 'system/phpthumb.php?' . urldecode($imageQuery);

            } else {
                $image = $this->ctx->getOption('manager_url', MODX_MANAGER_URL).'templates/default/images/restyle/nopreview.jpg';
                $this->xpdo->log(modX::LOG_LEVEL_ERROR,$this->get('name').' ('.$this->get('id').') MediaSource  could not create a thumbnail for file: '.$path);
            }
        }

        return [
            'src' => $image,
            'width' => $width,
            'height' => $height
        ];
    }

    /**
     * @param $path
     *
     * @return array|bool
     */
    protected function getImageDimensions($path)
    {
        if (isset($this->file_sizes[$path])) {
            $file_size = $this->file_sizes[$path];

        } else {
            /** @var League\Flysystem\Handler $file */
            $file = $this->filesystem->get($path);
            $size = @getimagesize($this->getBasePath().$file->getPath());
            if (is_array($size)) {
                // make this human readable
                $file_size = [
                    'width' => $size[0],
                    'height' => $size[1]
                ];
            } else {
                $file_size = false;
            }
            $this->file_sizes[$path] = $file_size;
        }

        return $file_size;
    }

    /**
     * Tells if a file is a binary file (some sort of text file) or not.
     * @param string $file
     *
     * @return boolean True if a binary file.
     */
    protected function isFileBinary($file) {
        $file_is_text = false;

        $stream_info = $this->filesystem->readStream($file);
        $stream = false;
        if (is_resource($stream_info)) {
            $stream = $stream_info;

        } elseif (is_array($stream_info) && isset($stream_info['stream']) && is_resource($stream_info['stream'])) {
            $stream = $stream_info['stream'];
        }

        if (is_resource($stream)) {
            $partial_contents = stream_get_contents($stream, 512);
            fclose($stream);
            @clearstatcache();
            $file_is_text = (substr_count($partial_contents, "^ -~" /*. "^\r\n"*/) / 512 > 0.3) || (substr_count($partial_contents, "\x00") > 0) ? false : true;
        }

        return $file_is_text;
    }

    /**
     * Tells if a file contents is a binary file (some sort of text file) or not.
     * @param string $file_contents
     *
     * @return bool
     */
    protected function isFileContentBinary($file_contents)
    {
        $content = str_replace(array("\n", "\r", "\t"), '', $file_contents);
        return ctype_print($content) ? false : true;
    }


    /**
     * @deprecated
     * Override this in child classes, unique to every Adapter
     *
     * @param $path - relative file path
     *
     * @return string
     */
    protected function getOctalPerms($path)
    {
        return '';
    }

    /**
     * Override this in child classes, unique to every Adapter
     *
     * @param $path - relative file path
     *
     * @return bool
     */
    protected function isFileWritable($path)
    {
        return true;
    }
}
