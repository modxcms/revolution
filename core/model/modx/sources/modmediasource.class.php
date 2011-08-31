<?php
/**
 * @package modx
 * @subpackage mysql
 */
/**
 * @package modx
 * @subpackage sources
 */
class modMediaSource extends xPDOSimpleObject {
    /** @var modX|xPDO $xpdo */
    public $xpdo;
    /** @var modContext $ctx */
    public $ctx;

    public $properties = array();
    public $permissions = array();
    public $errors = array();

    /**
     * Get the default MODX filesystem source
     * @static
     * @param xPDO|modX $xpdo A reference to an xPDO instance
     * @param int $defaultSourceId
     * @return An|null|object
     */
    public static function getDefaultSource(xPDO &$xpdo,$defaultSourceId = 1) {
        /** @var modMediaSource $defaultSource */
        $defaultSource = $xpdo->getObject('sources.modMediaSource',array(
            'id' => $defaultSourceId,
        ));
        if (empty($defaultSource)) {
            $defaultSource = $xpdo->getObject('sources.modMediaSource',array(
                'name' => 'Filesystem',
            ));
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
     * @return bool
     */
    public function initialize() {
        $this->getPermissions();
        return true;
    }

    /**
     * Setup the request properties for the source, determining any request-specific actions
     * @param array $scriptProperties
     * @return array
     */
    public function setRequestProperties(array $scriptProperties = array()) {
        if (empty($this->properties)) $this->properties = array();
        $this->properties = array_merge($this->properties,$scriptProperties);
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
     * Return an array of files and folders at this current level in the directory structure
     *
     * @param string $dir
     * @return array
     */
    public function getFolderList($dir) { return array(); }
    public function createFolder($folderName) { return true; }
    public function removeFolder($folderPath) { return true; }
    public function renameFolder($oldPath,$newPath) {}
    public function updateFolder() {}
    public function uploadToFolder() {}

    public function getFile($filePath) {}
    public function updateFile($filePath,$content) {}
    public function removeFile($filePath) {}
    public function renameFile() {}

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

    public function getDefaultProperties() {
        return array();
    }

    public function getProperties() {
        $properties = $this->get('properties');
        $defaultProperties = $this->getDefaultProperties();
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
        return $this->prepareProperties($properties);
    }

    protected function prepareProperties(array $properties = array()) {
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
        }
        return $properties;
    }

    public function setProperties($properties, $merge = false) {
        $default = $this->getDefaultProperties();

        foreach ($properties as $k => $prop) {
            if (array_key_exists($prop['name'],$default)) {
                if ($prop['value'] == $default[$prop['name']]['value']) {
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

    public function prepareSrcForThumb($src) {
        /* dont strip stuff for absolute URLs */
        if (substr($src,0,4) != 'http') {
            if (strpos($src,'/') !== 0) {
                $properties = $this->getProperties();
                $src = $properties['basePath']['value'].$src;
                if (!empty($properties['basePath']['value'])) {
                    $src = $this->ctx->getOption('base_path',null,MODX_BASE_PATH).$src;
                }
            }
            /* strip out double slashes */
            $src = str_replace(array('///','//'),'/',$src);

            /* check for file existence if local url */
            if (empty($src) || !file_exists($src)) {
                if (file_exists('/'.$src)) {
                    $src = '/'.$src;
                } else {
                    return '';
                }
            }
        }
        return $src;
    }
}