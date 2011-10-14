<?php
/**
 * Removes a content type
 *
 * @param integer $id The ID of the content type
 *
 * @package modx
 * @subpackage processors.system.contenttype
 */
class modContentTypeRemoveProcessor extends modProcessor {
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
        if ($this->isInUse()) {
            return $this->failure($this->modx->lexicon('content_type_err_in_use'));
        }

        if ($this->contentType->remove() == false) {
            return $this->failure($this->modx->lexicon('content_type_err_remove'));
        }

        $this->logManagerAction();
        return $this->success('',$this->contentType);
    }

    public function isInUse() {
        return $this->modx->getCount('modResource',array('content_type' => $this->contentType->get('id'))) > 0;
    }

    /**
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('content_type_delete','modContentType',$this->contentType->get('id'));
    }
}
return 'modContentTypeRemoveProcessor';