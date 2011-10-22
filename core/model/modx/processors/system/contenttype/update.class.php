<?php
/**
 * Update a content type from the grid. Sent through JSON-encoded 'data'
 * parameter.
 *
 * @param integer $id The ID of the content type
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
class modContentTypeUpdateProcessor extends modProcessor {
    /** @var modContentType $contentType */
    public $contentType;

    public function checkPermissions() {
        return $this->modx->hasPermission('content_types');
    }
    public function getLanguageTopics() {
        return array('content_type');
    }

    /**
     * Load the content type
     *
     * {@inheritDoc}
     * @return mixed
     */
    public function initialize() {
        $id = $this->getProperty('id');
        if (empty($id)) return $this->modx->lexicon('content_type_err_ns');
        $this->contentType = $this->modx->getObject('modContentType',$id);
        if (empty($this->contentType)) {
            return $this->modx->lexicon('content_type_err_nfs',array('id' => $id));
        }
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function process() {
        $fields = $this->getProperties();
        $fields['binary'] = !empty($fields['binary']) ? true : false;
        
        if (!$this->validate($fields)) {
            $this->failure();
        }

        $this->contentType->fromArray($fields);

        $refresh = $this->contentType->isDirty('file_extensions') && $this->modx->getCount('modResource', array('content_type' => $this->contentType->get('id')));
        if ($this->contentType->save() == false) {
            $this->modx->error->checkValidation($this->contentType);
            return $this->failure($this->modx->lexicon('content_type_err_save'));
        }
        if ($refresh) {
            $this->modx->call('modResource', 'refreshURIs', array(&$this->modx));
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
        $this->modx->logManagerAction('content_type_save','modContentType',$this->contentType->get('id'));
    }
}
return 'modContentTypeUpdateProcessor';