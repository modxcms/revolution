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
 * Gets a list of TVs, marking ones associated with the template.
 *
 * @param integer $template (optional) The template which the TVs are associated
 * to.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.template.tv
 */

class modElementTemplateTvGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modTemplate';
    public $primaryKeyField = 'template';
    public $objectType = 'template';
    public $permission = array('view_tv' => true, 'view_template' => true);
    public $languageTopics = array('template');

    /**
     * Prepare conditions for TV list
     * @return array
     */
    public function prepareConditions() {
        $conditions = array();

        $category = (integer) $this->getProperty('category', 0);
        if ($category) {
            $conditions['category'] = $category;
        }

        $search =  $this->getProperty('search', '');
        if (!empty($search)) {
            $conditions['name:LIKE'] = '%'.$search.'%';
            $conditions['OR:description:LIKE'] = '%'.$search.'%';
            $conditions['OR:caption:LIKE'] = '%'.$search.'%';
        }

        return $conditions;
    }

    /**
     * Load template which TVs are assigned to or new template
     * @return modTemplate
     */
    public function loadTemplate() {
        $templateId = $this->getProperty($this->primaryKeyField, 0);
        /** @var modTemplate $template */
        $template = ($templateId > 0) ?
            $this->modx->getObject($this->classKey, $templateId) :
            $this->modx->newObject($this->classKey);

        return $template;
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function getData() {
        $sort = $this->getProperty('sort');
        $dir = $this->getProperty('dir');
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));
        $conditions = $this->prepareConditions();

        $template = $this->loadTemplate();
        $tvList = $template->getTemplateVarList(array($sort => $dir), $limit, $start, $conditions);
        $data = array(
            'total' => $tvList['total'],
            'results' => $tvList['collection'],
        );
        return $data;
    }

    /**
     * {@inheritdoc}
     * @param xPDOObject $object
     * @return array|mixed
     */
    public function prepareRow(xPDOObject $object) {
        $tvArray = $object->get(array('id','name','caption','tv_rank','category_name'));
        $tvArray['access'] = (boolean)$object->get('access');

        $tvArray['perm'] = array();
        if ($this->modx->hasPermission('edit_tv')) {
            $tvArray['perm'][] = 'pedit';
        }

        return $tvArray;
    }
}

return 'modElementTemplateTvGetListProcessor';
