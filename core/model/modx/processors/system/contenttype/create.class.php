<?php
/**
 * Create a content type
 *
 * @param string $name The new name
 * @param string $description (optional) A short description
 * @param string $mime_type The MIME type for the content type
 * @param string $file_extensions A list of file extensions associated with this
 * type
 * @param string $headers Any headers to be sent with resources with this type
 * @param boolean $binary If true, will be sent as binary data
 *
 * @package modx
 * @subpackage processors.system.contenttype
 */
class modContentTypeCreateProcessor extends modProcessor {
    /** @var modContentType $contentType */
    public $contentType;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('content_types');
    }
    public function getLanguageTopics() {
        return array('content_type');
    }

    public function initialize() {
        $this->contentType = $this->modx->newObject('modContentType');
        return true;
    }

    public function process() {
        $fields = $this->getProperties();
        $fields['binary'] = !empty($fields['binary']) ? true : false;
        
        if (!$this->validate($fields)) {
            $this->failure();
        }

        $this->contentType->fromArray($fields);
        if ($this->contentType->save() == false) {
            $this->modx->error->checkValidation($this->contentType);
            return $this->failure($this->modx->lexicon('content_type_err_create'));
        }

        $this->logManagerAction();
        return $this->success('',$this->contentType);
    }

    /**
     * Validate against the fields
     * 
     * @param array $fields
     * @return boolean
     */
    public function validate(array $fields) {
        if (empty($fields['name'])) {
            $this->addFieldError('name',$this->modx->lexicon('content_type_err_ns_name'));
        }
        return !$this->hasErrors();
    }

    /**
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('content_type_create','modContentType',$this->contentType->get('id'));
    }
}
return 'modContentTypeCreateProcessor';