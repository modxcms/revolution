<?php
/**
 * Gets a list of Media Sources
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.source
 */
class modMediaSourceGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('source_view');
    }
    public function getLanguageTopics() {
        return array('sources');
    }
    public function initialize() {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 10,
            'sort' => 'name',
            'dir' => 'ASC',
            'showNone' => false,
            'query' => '',
            'streamsOnly' => false,
        ));
        return true;
    }
    public function process() {
        $data = $this->getData();

        $list = array();
        if ($this->getProperty('showNone')) {
            $list[] = array(
                'id' => 0,
                'name' => '('.$this->modx->lexicon('none').')',
                'description' => '',
            );
        }
        /** @var modMediaSource $source */
        foreach ($data['results'] as $source) {
            if (!$source->checkPolicy('list')) continue;
            $sourceArray = $this->prepareRow($source);
            if (!empty($sourceArray)) {
                $list[] = $sourceArray;
            }
        }
        return $this->outputArray($list,$data['total']);
    }

    /**
     * Get a collection of modMediaSource objects
     * 
     * @return array
     */
    public function getData() {
        $data = array();
        $limit = intval($this->getProperty('limit'));
        
        $c = $this->modx->newQuery('sources.modMediaSource');

        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array('modMediaSource.name:LIKE' => '%'.$query.'%'));
            $c->orCondition(array('modMediaSource.description:LIKE' => '%'.$query.'%'));
        }
        if ($this->getProperty('streamsOnly')) {
            $c->where(array(
                'modMediaSource.is_stream' => true,
            ));
        }
        $data['total'] = $this->modx->getCount('sources.modMediaSource',$c);
        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        if ($limit > 0) $c->limit($limit,$this->getProperty('start',0));
        
        $data['results'] = $this->modx->getCollection('sources.modMediaSource',$c);
        return $data;
    }

    /**
     * Prepare the source for iteration and output
     * 
     * @param modMediaSource $source
     * @return array
     */
    public function prepareRow(modMediaSource $source) {
        $canEdit = $this->modx->hasPermission('source_edit');
        $canSave = $this->modx->hasPermission('source_save');
        $canRemove = $this->modx->hasPermission('source_delete');

        $sourceArray = $source->toArray();

        $cls = array();
        if ($source->checkPolicy('save') && $canSave && $canEdit) $cls[] = 'pupdate';
        if ($source->checkPolicy('remove') && $canRemove) $cls[] = 'premove';
        if ($source->checkPolicy('copy') && $canSave) $cls[] = 'pduplicate';
        
        $sourceArray['cls'] = implode(' ',$cls);
        return $sourceArray;
    }
}
return 'modMediaSourceGetListProcessor';