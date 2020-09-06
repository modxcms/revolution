<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * The interface used for defining common methods for Media Source drivers
 * @package modx
 */
interface modMediaSourceInterface
    /**
     * Initialize the source, preparing it for usage.
     *
     * @return boolean
     */{
    public function initialize();

    /**
     * Return an array of containers at this current level in the container structure. Used for the tree
     * navigation on the files tree.
     *
     * @abstract
     * @param string $path
     * @return array
     */
    public function getContainerList($path);

    /**
     * Return a detailed list of objects in a specific path. Used for thumbnails in the Browser.
     *
     * @param string $path
     * @return array
     */
    public function getObjectsInContainer($path);

    /**
     * Create a container at the passed location with the passed name
     *
     * @abstract
     * @param string $name
     * @param string $parentContainer
     * @return boolean
     */
    public function createContainer($name,$parentContainer);

    /**
     * Remove the specified container
     *
     * @abstract
     * @param string $path
     * @return boolean
     */
    public function removeContainer($path);

    /**
     * Rename a container
     *
     * @abstract
     * @param string $oldPath
     * @param string $newName
     * @return boolean
     */
    public function renameContainer($oldPath,$newName);

    /**
     * Upload objects to a specific container
     *
     * @abstract
     * @param string $container
     * @param array $objects
     * @return boolean
     */
    public function uploadObjectsToContainer($container,array $objects = array());

    /**
     * Get the contents of an object
     *
     * @abstract
     * @param string $objectPath
     * @return boolean
     */
    public function getObjectContents($objectPath);

    /**
     * Update the contents of a specific object
     *
     * @abstract
     * @param string $objectPath
     * @param string $content
     * @return boolean
     */
    public function updateObject($objectPath,$content);

    /**
     * Create an object from a path
     *
     * @param string $objectPath
     * @param string $name
     * @param string $content
     * @return boolean|string
     */
    public function createObject($objectPath,$name,$content);

    /**
     * Remove an object
     *
     * @abstract
     * @param string $objectPath
     * @return boolean
     */
    public function removeObject($objectPath);

    /**
     * Rename a file/object
     *
     * @abstract
     * @param string $oldPath
     * @param string $newName
     * @return bool
     */
    public function renameObject($oldPath,$newName);

    /**
     * Get the openTo path for this source, used with TV input types and Static Elements/Resources
     *
     * @param string $value
     * @param array $parameters
     * @return string
     */
    public function getOpenTo($value,array $parameters = array());

    /**
     * Get the base path for this source. Only applicable to sources that are streams, used for determining
     * the base path with Static objects.
     *
     * @param string $object An optional file to find the base path with
     * @return string
     */
    public function getBasePath($object = '');

    /**
     * Get the base URL for this source. Only applicable to sources that are streams; used for determining the base
     * URL with Static objects and downloading objects.
     *
     * @abstract
     * @param string $object
     * @return void
     */
    public function getBaseUrl($object = '');

    /**
     * Get the URL for an object in this source. Only applicable to sources that are streams; used for determining
     * the base URL with Static objects and downloading objects.
     *
     * @abstract
     * @param string $object
     * @return void
     */
    public function getObjectUrl($object = '');

    /**
     * Move a file or folder to a specific location
     *
     * @param string $from The location to move from
     * @param string $to The location to move to
     * @param string $point The type of move; append, above, below
     * @return boolean
     */
    public function moveObject($from,$to,$point = 'append');

    /**
     * Get the name of this source type, ie, "File System"
     * @return string
     */
    public function getTypeName();

    /**
     * Get a short description of this source type
     * @return string
     */
    public function getTypeDescription();

    /**
     * Get the default properties for this source. Override this in your custom source driver to provide custom
     * properties for your source type.
     * @return array
     */
    public function getDefaultProperties();

}
/**
 * An abstract base class used for determining functionality of different media source drivers. Extend this class in
 * and implement modMediaSourceInterface in your driver implementations to provide custom functionality for different
 * types of media sources.
 *
 * Of course, in your getContainerList method, you can define the context menu items for the tree, so not all of these
 * methods might need to be implemented, depending on your situation. You can also provide custom actions for your
 * source type, depending on the behavior you might need.
 *
 * @package modx
 * @subpackage sources
 */
class modMediaSource extends modAccessibleSimpleObject implements modMediaSourceInterface {
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
        if (!$this->checkPolicy('view')) {
            return false;
        }

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
        return $this->checkPolicy('view');
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

    public function getContainerList($path) { return array(); }
    public function getObjectsInContainer($path) { return array(); }
    public function createContainer($name,$parentContainer) { return true; }
    public function removeContainer($path) { return true; }
    public function renameContainer($oldPath,$newName) { return true; }
    public function updateContainer() { return true; }
    public function uploadObjectsToContainer($container,array $objects = array()) { return true; }
    public function getObjectContents($objectPath) { return true; }
    public function updateObject($objectPath,$content) { return true; }
    public function createObject($objectPath,$name,$content) { return true; }
    public function removeObject($objectPath) { return true; }
    public function renameObject($oldPath,$newName) { return true; }
    public function getBasePath($object = '') { return ''; }
    public function getBaseUrl($object = '') { return ''; }
    public function getObjectUrl($object = '') { return ''; }
    public function moveObject($from,$to,$point = 'append') { return true; }
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
        /* dont strip stuff for absolute URLs */

        if (substr($src,0,4) != 'http') {
            if (strpos($src,'/') !== 0) {
                $properties = $this->getPropertyList();
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
}
