<?php
/**
 * Gets a list of content types
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.contenttype
 */
class modContentTypeGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('content_types');
    }
    public function getLanguageTopics() {
        return array('content_type');
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 10,
            'start' => 0,
            'sort' => 'name',
            'dir' => 'ASC',
        ));
        return true;
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process() {
        $data = $this->getData();

        $list = array();
        /** @var modContentType $contentType */
        foreach ($data['results'] as $contentType) {
            $list[] = $contentType->toArray();
        }
        return $this->outputArray($list,$data['total']);
    }

    /**
     * Get a collection of Content Types
     * @return array
     */
    public function getData() {
        $data = array();
        $limit = $this->getProperty('limit');
        $isLimit = !empty($limit);

        $c = $this->modx->newQuery('modContentType');
        $data['total'] = $this->modx->getCount('modContentType');
        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        if ($isLimit) {
            $c->limit($limit,$this->getProperty('start'));
        }
        $data['results'] = $this->modx->getIterator('modContentType',$c);
        return $data;
    }
}
return 'modContentTypeGetListProcessor';