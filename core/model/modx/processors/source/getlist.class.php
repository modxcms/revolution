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

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'showNone' => false,
            'query' => '',
            'streamsOnly' => false,
        ));
        return $initialized;
    }

    /**
     * {@inheritDoc}
     * @return string
     */
    public function getSortClassKey() {
        return 'modMediaSource';
    }

    /**
     * {@inheritDoc}
     * @param array $list
     * @return array
     */
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

    /**
     * {@inheritDoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
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
     * Filter the query by the valueField of MODx.combo.MediaSource to get the initially value displayed right
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c) {
        $id = $this->getProperty('id','');
        if (!empty($id)) {
            $c->where(array(
                $this->getSortClassKey() . '.id:IN' => is_string($id) ? explode(',', $id) : $id,
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
