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
class modContentTypeUpdateFromGridProcessor extends modProcessor {
    /** @var array $records */
    public $records;

    public function checkPermissions() {
        return $this->modx->hasPermission('content_types');
    }
    public function getLanguageTopics() {
        return array('content_type');
    }

    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $this->record = $this->modx->fromJSON($data);
        if (empty($this->record)) return $this->modx->lexicon('invalid_data');
        return true;
    }

    public function process() {
        $refresh = array();
        $field = $this->record;
        if (empty($field['id'])) {
            return $this->failure($this->modx->lexicon('content_type_err_ns'));
        };
        /** @var modContentType $contentType */
        $contentType = $this->modx->getObject('modContentType',$field['id']);
        if (!$contentType) {
            return $this->failure($this->modx->lexicon('content_type_err_nfs', array('id', $field['id'])));
        };

        /* save content type */
        $field['binary'] = !empty($field['binary']) ? true : false;
        $contentType->fromArray($field);

        $refresh[] = $contentType->isDirty('file_extensions') && $this->modx->getCount('modResource', array('content_type' => $contentType->get('id')));
        if ($contentType->save() == false) {
            $msg = $this->modx->error->checkValidation($contentType);
            return $this->failure(empty($mg) ? $this->modx->lexicon('content_type_err_save') : $msg);
        }

        /* log manager action */
        $this->modx->logManagerAction('content_type_save','modContentType',$contentType->get('id'));

        if (array_search(true, $refresh, true) !== false) {
            $this->modx->call('modResource', 'refreshURIs', array(&$this->modx));
        }
        return $this->success();
    }
}
return 'modContentTypeUpdateFromGridProcessor';
