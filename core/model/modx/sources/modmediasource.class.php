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
     * @return array
     */
    public function getFolderList() { return array(); }
    public function createFolder($folderName) { return true; }
    public function removeFolder($folderPath) { return true; }
    public function renameFolder($oldPath,$newPath) {}
    public function updateFolder() {}
    public function uploadToFolder() {}

    public function getFile($filePath) {}
    public function updateFile($filePath,$content) {}
    public function removeFile($filePath) {}
    public function renameFile() {}
}