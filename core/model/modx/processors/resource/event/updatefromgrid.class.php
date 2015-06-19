<?php
/**
 * Update a resource from the site schedule grid.
 *
 * @param json $data A JSON array of data to update with.
 *
 * @package modx
 * @subpackage processors.resource.event
 */
class modResourceEventUpdateFromGrid extends modProcessor {
    /** @var modResource $resource */
    public $resource;
    public function checkPermissions() {
        return $this->modx->hasPermission('save_document');
    }
    public function getLanguageTopics() {
        return array('resource');
    }

    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('resource_err_ns');
        $data = $this->modx->fromJSON($data);
        if (empty($data) || empty($data['id'])) return $this->modx->lexicon('resource_err_ns');

        $this->setProperties($data);

        $this->resource = $this->modx->getObject('modResource',$data['id']);
        if (empty($this->resource)) return $this->modx->lexicon('resource_err_nf');
        return true;
    }

    public function process() {
        if (!$this->validate()) {
            return $this->failure();
        }
        $this->resource->fromArray($this->getProperties());

        if ($this->resource->save() === false) {
            return $this->failure($this->modx->lexicon('resource_err_save'));
        }

        return $this->success();
    }

    public function validate() {
        $publishDate = $this->getProperty('pub_date');
        if (!empty($publishDate)) {
            $this->setProperty('pub_date',strftime('%Y-%m-%d %H:%M',strtotime($publishDate)));
        }

        $unPublishDate = $this->getProperty('unpub_date');
        if (!empty($unPublishDate)) {
            $this->setProperty('unpub_date',strftime('%Y-%m-%d %H:%M',strtotime($unPublishDate)));
        }
        return true;
    }
}
return 'modResourceEventUpdateFromGrid';