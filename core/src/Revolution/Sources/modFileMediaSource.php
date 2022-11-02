<?php
namespace MODX\Revolution\Sources;

use Exception;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;
use League\Flysystem\Visibility;
use xPDO\xPDO;

/**
 * Implements a file-system-based media source, allowing manipulation and management of files on the server's
 * location. Supports basePath and baseUrl parameters, similar to Revolution 2.1 and prior's filemanager_* settings.
 *
 * @package MODX\Revolution\Sources
 */
class modFileMediaSource extends modMediaSource
{
    protected $visibility_files = true;
    protected $visibility_dirs = true;


    /**
     * @return bool
     */
    public function initialize()
    {
        parent::initialize();

        try {
            $localAdapter = new LocalFilesystemAdapter(
                // Determine the root directory
                $this->getBasePath(),

                // If users would like to modify the private values, they can create
                // new system settings: private_file_permissions and private_folder_permissions.
                PortableVisibilityConverter::fromArray([
                    'file' => [
                        'public' => octdec($this->xpdo->getOption('new_file_permissions', [], '0644')),
                        'private' => octdec($this->xpdo->getOption('private_file_permissions', [], '0600')),
                    ],
                    'dir' => [
                        'public' => octdec($this->xpdo->getOption('new_folder_permissions', [], '0755')),
                        'private' => octdec($this->xpdo->getOption('private_folder_permissions', [], '0700')),
                    ],
                ],Visibility::PUBLIC),

                // Write flags
                LOCK_EX,

                // How to deal with links, either DISALLOW_LINKS or SKIP_LINKS
                // Disallowing them causes exceptions when encountered
                LocalFilesystemAdapter::SKIP_LINKS
            );

        } catch (Exception $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,
                $this->xpdo->lexicon('source_err_init', ['source' => $this->get('name')]) . ' ' . $e->getMessage());

            return false;
        }

        $this->loadFlySystem($localAdapter);

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

        return $this->xpdo->lexicon('source_type.file');
    }


    /**
     * Get the description of this source type
     *
     * @return string
     */
    public function getTypeDescription()
    {
        $this->xpdo->lexicon->load('source');

        return $this->xpdo->lexicon('source_type.file_desc');
    }


    /**
     * Get the default properties for the filesystem media source type.
     *
     * @return array
     */
    public function getDefaultProperties()
    {
        return [
            'basePath' => [
                'name' => 'basePath',
                'desc' => 'prop_file.basePath_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ],
            'basePathRelative' => [
                'name' => 'basePathRelative',
                'desc' => 'prop_file.basePathRelative_desc',
                'type' => 'combo-boolean',
                'options' => '',
                'value' => true,
                'lexicon' => 'core:source',
            ],
            'baseUrl' => [
                'name' => 'baseUrl',
                'desc' => 'prop_file.baseUrl_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ],
            'baseUrlRelative' => [
                'name' => 'baseUrlRelative',
                'desc' => 'prop_file.baseUrlRelative_desc',
                'type' => 'combo-boolean',
                'options' => '',
                'value' => true,
                'lexicon' => 'core:source',
            ],
            'allowedFileTypes' => [
                'name' => 'allowedFileTypes',
                'desc' => 'prop_file.allowedFileTypes_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '',
                'lexicon' => 'core:source',
            ],
            'imageExtensions' => [
                'name' => 'imageExtensions',
                'desc' => 'prop_file.imageExtensions_desc',
                'type' => 'textfield',
                'value' => 'jpg,jpeg,png,gif,svg,webp',
                'lexicon' => 'core:source',
            ],
            'thumbnailType' => [
                'name' => 'thumbnailType',
                'desc' => 'prop_file.thumbnailType_desc',
                'type' => 'list',
                'options' => [
                    ['name' => 'PNG', 'value' => 'png'],
                    ['name' => 'JPG', 'value' => 'jpg'],
                    ['name' => 'GIF', 'value' => 'gif'],
                    ['name' => 'WebP', 'value' => 'webp'],
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
                'desc' => 'prop_file.skipFiles_desc',
                'type' => 'textfield',
                'options' => '',
                'value' => '.svn,.git,_notes,nbproject,.idea,.DS_Store',
                'lexicon' => 'core:source',
            ],
        ];
    }


    /**
     * Prepare the output values for image/file TVs by prefixing the baseUrl property to them
     *
     * @param string $value
     *
     * @return string
     */
    public function prepareOutputUrl($value)
    {
        $properties = $this->getPropertyList();
        if (!empty($properties['baseUrl'])) {
            $value = $properties['baseUrl'] . $value;
        }

        return $value;
    }


    /**
     * Get the base path for this source. Only applicable to sources that are streams.
     *
     * @param string $object An optional file to find the base path of
     *
     * @return string
     */
    public function getBasePath($object = '')
    {
        $bases = $this->getBases($object);

        return $bases['pathAbsolute'];
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
        $bases = $this->getBases($object);

        return $bases['urlAbsolute'];
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
        return $this->getBaseUrl() . $object;
    }
}
