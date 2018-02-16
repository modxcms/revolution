<?php
/**
 * @package modx
 * @subpackage sources
 */
require_once MODX_CORE_PATH . 'model/modx/sources/modmediasource.class.php';

use League\Flysystem\Adapter\Local;

/**
 * Implements a file-system-based media source, allowing manipulation and management of files on the server's
 * location. Supports basePath and baseUrl parameters, similar to Revolution 2.1 and prior's filemanager_* settings.
 *
 * @package modx
 * @subpackage sources
 */
class modFileMediaSource extends modMediaSource {
    /** @var modFileHandler */
    public $fileHandler;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        parent::initialize();

        //$this->use_chmod = true;

        $localAdapter = new Local($this->getBasePath());
        // Create the cache store
        $this->loadFlySystem($localAdapter);

        return true;
    }

    /**
     * Get base paths/urls and sanitize incoming paths
     *
     * @param string $path A path to the active directory
     * @return array
     */
    public function getBases($path = '') {
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
    public function getEditActionId() {
        return 'system/file/edit';
    }

    /**
     * @deprecated
     * Chmod a specific folder
     *
     * @param string $directoryPath
     * @param string $mode
     * @return boolean
     */
    public function chmodContainer($directoryPath,$mode) {
        /** @var modDirectory $directory */
        $directory = $this->fileHandler->make($directoryPath);

        /* verify target path is a directory and writable */
        if (!($directory instanceof modDirectory)) {
            $this->addError('mode',$this->xpdo->lexicon('file_folder_err_invalid').': '.$directoryPath);
            return false;
        }
        if (!$directory->isReadable() || !$directory->isWritable()) {
            $this->addError('mode',$this->xpdo->lexicon('file_folder_err_perms_upload').': '.$directoryPath);
            return false;
        }

        if (!$directory->isValidMode($mode)) {
            $this->addError('mode',$this->xpdo->lexicon('file_err_chmod_invalid'));
            return false;
        }

        if (!$directory->chmod($mode)) {
            $this->addError('mode',$this->xpdo->lexicon('file_err_chmod'));
            return false;
        }

        $this->xpdo->logManagerAction('directory_chmod','',$directoryPath);
        return true;
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
     * Get the default properties for the filesystem media source type.
     *
     * @return array
     */
    public function getDefaultProperties() {
        return array(
            'basePath' => array(
                'name' => 'basePath',
                'desc' => 'prop_file.basePath_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ),
            'basePathRelative' => array(
                'name' => 'basePathRelative',
                'desc' => 'prop_file.basePathRelative_desc',
                'type' => 'combo-boolean',
                'options' => '',
                'value' => true,
                'lexicon' => 'core:source',
            ),
            'baseUrl' => array(
                'name' => 'baseUrl',
                'desc' => 'prop_file.baseUrl_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ),
            'baseUrlRelative' => array(
                'name' => 'baseUrlRelative',
                'desc' => 'prop_file.baseUrlRelative_desc',
                'type' => 'combo-boolean',
                'options' => '',
                'value' => true,
                'lexicon' => 'core:source',
            ),
            'allowedFileTypes' => array(
                'name' => 'allowedFileTypes',
                'desc' => 'prop_file.allowedFileTypes_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ),
            'imageExtensions' => array(
                'name' => 'imageExtensions',
                'desc' => 'prop_file.imageExtensions_desc',
                'type' => 'textfield',
                'value' => 'jpg,jpeg,png,gif,svg',
                'lexicon' => 'core:source',
            ),
            'thumbnailType' => array(
                'name' => 'thumbnailType',
                'desc' => 'prop_file.thumbnailType_desc',
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
                'desc' => 'prop_file.skipFiles_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '.svn,.git,_notes,nbproject,.idea,.DS_Store',
                'lexicon' => 'core:source',
            ),
        );
    }

    /**
     * Prepare the output values for image/file TVs by prefixing the baseUrl property to them
     *
     * @param string $value
     * @return string
     */
    public function prepareOutputUrl($value) {
        $properties = $this->getPropertyList();
        if (!empty($properties['baseUrl'])) {
            $value = $properties['baseUrl'].$value;
        }
        return $value;
    }


    /**
     * Get the base path for this source. Only applicable to sources that are streams.
     *
     * @param string $object An optional file to find the base path of
     * @return string
     */
    public function getBasePath($object = '') {
        $bases = $this->getBases($object);
        return $bases['pathAbsolute'];
    }

    /**
     * Get the base URL for this source. Only applicable to sources that are streams.
     *
     * @param string $object An optional object to find the base url of
     * @return string
     */
    public function getBaseUrl($object = '') {
        $bases = $this->getBases($object);
        return $bases['urlAbsolute'];
    }

    /**
     * Get the absolute URL for a specified object. Only applicable to sources that are streams.
     *
     * @param string $object
     * @return string
     */
    public function getObjectUrl($object = '') {
        return $this->getBaseUrl().$object;
    }

    /**
     * @deprecated
     * @param $path - relative file path
     *
     * @return string
     */
    protected function getOctalPerms($path)
    {
        /** @var SplFileInfo $file */
        $file = new SplFileInfo($this->getBasePath().$path);
        return substr(sprintf('%o', $file->getPerms()), -4);
    }
}
