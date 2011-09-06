<?php
/**
 * @package modx
 * @subpackage sources
 */
/**
 * An abstract base class used for determining functionality of different media source drivers. Extend this class in
 * your driver implementations to provide custom functionality for different types of media sources.
 * 
 * @package modx
 * @subpackage sources
 */
class modMediaSource extends modAccessibleSimpleObject {
    /** @var modX|xPDO $xpdo */
    public $xpdo;
    /** @var modContext $ctx */
    public $ctx;
    /** @var array $properties */
    public $properties = array();
    /** @var array $permissions */
    public $permissions = array();
    /** @var array $errors */
    public $errors = array();

    /**
     * Get the default MODX filesystem source
     * @static
     * @param xPDO|modX $xpdo A reference to an xPDO instance
     * @param int $defaultSourceId
     * @param boolean $fallbackToDefault
     * @return An|null|object
     */
    public static function getDefaultSource(xPDO &$xpdo,$defaultSourceId = 1,$fallbackToDefault = true) {
        /** @var modMediaSource $defaultSource */
        $defaultSource = $xpdo->getObject('sources.modMediaSource',array(
            'id' => $defaultSourceId,
        ));
        if (empty($defaultSource) && $fallbackToDefault) {
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
     * Return an array of files and folders at this current level in the directory structure.
     *
     * @abstract
     * @param string $dir
     * @return array
     */
    public function getFolderList($dir) { return array(); }

    /**
     * Return a detailed list of files in a specific directory. Used for thumbnails in the Browser.
     * 
     * @param string $dir
     * @return array
     */
    public function getFilesInDirectory($dir) { return array(); }

    /**
     * Create a folder at the passed location
     * 
     * @abstract
     * @param string $folderName
     * @return boolean
     */
    public function createFolder($folderName) { return true; }

    /**
     * Remove the specified folder
     *
     * @abstract
     * @param string $folderPath
     * @return boolean
     */
    public function removeFolder($folderPath) { return true; }

    /**
     * Rename a folder
     * 
     * @abstract
     * @param string $oldPath
     * @param string $newName
     * @return boolean
     */
    public function renameFolder($oldPath,$newName) { return true; }

    /**
     * Update a folder
     *
     * @return boolean
     */
    public function updateFolder() { return true; }

    /**
     * Upload files to a specific folder
     *
     * @abstract
     * @param string $targetDirectory
     * @param array $files
     * @return boolean
     */
    public function uploadToFolder($targetDirectory,$files) { return true; }

    /**
     * Get the contents of a file
     * 
     * @abstract
     * @param string $filePath
     * @return bool
     */
    public function getFile($filePath) { return true; }

    /**
     * Update the contents of a specific file
     *
     * @abstract
     * @param string $filePath
     * @param string $content
     * @return boolean
     */
    public function updateFile($filePath,$content) { return true; }

    /**
     * Remove a file
     *
     * @abstract
     * @param string $filePath
     * @return bool
     */
    public function removeFile($filePath) { return true; }

    /**
     * Rename a file
     *
     * @abstract
     * @param string $oldPath
     * @param string $newName
     * @return bool
     */
    public function renameFile($oldPath,$newName) { return true; }

    public function getOpenTo($value,array $parameters = array()) {
        $properties = $this->getPropertyList();
        return $properties['baseUrl'].dirname($value).'/';
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
     * Get the default properties for this source. Override this in your custom source driver to provide custom
     * properties for your source type.
     * @return array
     */
    public function getDefaultProperties() {
        return array();
    }

    /**
     * Get the properties on this source
     * @return array
     */
    public function getProperties() {
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
        return $this->prepareProperties($properties);
    }

    /**
     * Get all properties in key => value format
     * @return array
     */
    public function getPropertyList() {
        $properties = $this->getProperties();
        $list = array();
        foreach ($properties as $property) {
            $value = $property['value'];
            if ($property['xtype'] == 'combo-boolean') {
                $value = empty($property['value']) && $property['value'] != 'true' ? false : true;
            }
            $list[$property['name']] = $value;
        }
        return $list;
    }

    /**
     * Translate any needed properties
     * @param array $properties
     * @return array
     */
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

    public function prepareOutputUrl($value) {
        return $value;
    }

    public function findPolicy($context = '') {
        $policy = array();
        $enabled = true;
        $context = 'mgr';//!empty($context) ? $context : 'mgr';
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
                if ($this->get('id') == 8) {
                    $query->prepare($bindings);
                    //echo $query->toSQL(); die();
                }
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
     * @return bool
     */
    public function checkPolicy($criteria, $targets = null) {
        if ($criteria == 'load') {
            $success = true;
        } else {
            $success = parent::checkPolicy($criteria,$targets);
        }
        return $success;
    }
}