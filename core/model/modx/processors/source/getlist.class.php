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
class modMediaSourceGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'sources.modMediaSource';
    public $languageTopics = array('source');
    public $permission = 'source_view';

    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'showNone' => false,
            'query' => '',
            'streamsOnly' => false,
        ));
        return $initialized;
    }

    public function getSortClassKey() {
        return 'modMediaSource';
    }

    public function beforeIteration(array $list) {
        if ($this->getProperty('showNone')) {
            $list[] = array(
                'id' => 0,
                'name' => '('.$this->modx->lexicon('none').')',
                'description' => '',
            );
        }
        return $list;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
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
        return $c;
    }

    /**
     * Prepare the source for iteration and output
     *
     * @param xPDOObject|modAccessibleObject|modMediaSource $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $canEdit = $this->modx->hasPermission('source_edit');
        $canSave = $this->modx->hasPermission('source_save');
        $canRemove = $this->modx->hasPermission('source_delete');

        $objectArray = $object->toArray();
        $objectArray['iconCls'] = $this->modx->getOption('mgr_source_icon', null, 'icon-folder-open-o');

        $props = $object->getPropertyList();
        if (isset($props['iconCls']) && !empty($props['iconCls'])) {
            $objectArray['iconCls'] = $props['iconCls'];
        }

        $cls = array();
        if ($object->checkPolicy('save') && $canSave && $canEdit) $cls[] = 'pupdate';
        if ($object->checkPolicy('remove') && $canRemove) $cls[] = 'premove';
        if ($object->checkPolicy('copy') && $canSave) $cls[] = 'pduplicate';

        $objectArray['cls'] = implode(' ',$cls);
        return $objectArray;
    }
}
return 'modMediaSourceGetListProcessor';
