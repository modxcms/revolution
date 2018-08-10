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
 * Returns resource data.
 *
 * @param integer $id The ID of the resource
 * @return array
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourceDataProcessor extends modProcessor {
    /** @var modResource $resource */
    public $resource;
    public function checkPermissions() {
        return $this->modx->hasPermission('view');
    }
    public function getLanguageTopics() {
        return array('resource');
    }
    public function initialize() {
        $id = $this->getProperty('id',false);
        if (empty($id)) return $this->modx->lexicon('resource_err_ns');
        $c = $this->modx->newQuery('modResource');
        $c->select(array(
            $this->modx->getSelectColumns('modResource','modResource'),
            'template_name' => 'Template.templatename',
            'creator' => 'CreatedBy.username',
            'editor' => 'EditedBy.username',
            'publisher' => 'PublishedBy.username',
        ));
        $c->leftJoin('modTemplate','Template');
        $c->leftJoin('modUser','CreatedBy');
        $c->leftJoin('modUser','EditedBy');
        $c->leftJoin('modUser','PublishedBy');
        $c->where(array(
            'modResource.id' => $id,
        ));
        $this->resource = $this->modx->getObject('modResource',$c);
        if (empty($this->resource)) {
            return $this->modx->lexicon('resource_err_nfs',array('id' => $id));
        }
        if (!$this->resource->checkPolicy('view')) {
            return $this->modx->lexicon('permission_denied');
        }
        return true;
    }

    public function process() {
        $resourceArray = $this->resource->toArray('',true,true);
        $resourceArray = $this->getChanges($resourceArray);

        /* template */
        if (empty($resourceArray['template_name'])) {
            $resourceArray['template_name'] = $this->modx->lexicon('empty_template');
        }

        /* source */
        $resourceArray['buffer'] = $this->getCacheSource();
        return $this->success('',$resourceArray);
    }

    public function getCacheSource() {
        $this->resource->_contextKey= $this->resource->get('context_key');
        $buffer = $this->modx->cacheManager->get($this->resource->getCacheKey(), array(
            xPDO::OPT_CACHE_KEY => $this->modx->getOption('cache_resource_key', null, 'resource'),
            xPDO::OPT_CACHE_HANDLER => $this->modx->getOption('cache_resource_handler', null, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer) $this->modx->getOption('cache_resource_format', null, $this->modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ));
        if ($buffer) {
            $buffer = $buffer['resource']['_content'];
        }
        return !empty($buffer) ? $buffer : $this->modx->lexicon('resource_notcached');
    }

    public function getChanges(array $resourceArray) {
        $emptyDate = '0000-00-00 00:00:00';
        $resourceArray['pub_date'] = !empty($resourceArray['pub_date']) && $resourceArray['pub_date'] != $emptyDate ? $resourceArray['pub_date'] : $this->modx->lexicon('none');
        $resourceArray['unpub_date'] = !empty($resourceArray['unpub_date']) && $resourceArray['unpub_date'] != $emptyDate ? $resourceArray['unpub_date'] : $this->modx->lexicon('none');
        $resourceArray['status'] = $resourceArray['published'] ? $this->modx->lexicon('resource_published') : $this->modx->lexicon('resource_unpublished');

        $server_offset_time= floatval($this->modx->getOption('server_offset_time',null,0)) * 3600;
        $format = $this->modx->getOption('manager_date_format') .' '. $this->modx->getOption('manager_time_format');
        $resourceArray['createdon_adjusted'] = date($format, strtotime($this->resource->get('createdon')) + $server_offset_time);
        $resourceArray['createdon_by'] = $this->resource->get('creator');
        if (!empty($resourceArray['editedon']) && $resourceArray['editedon'] != $emptyDate) {
            $resourceArray['editedon_adjusted'] = date($format, strtotime($this->resource->get('editedon')) + $server_offset_time);
            $resourceArray['editedon_by'] = $this->resource->get('editor');
        } else {
            $resourceArray['editedon_adjusted'] = $this->modx->lexicon('none');
            $resourceArray['editedon_by'] = $this->modx->lexicon('none');
        }
        if (!empty($resourceArray['publishedon']) && $resourceArray['publishedon'] != $emptyDate) {
            $resourceArray['publishedon_adjusted'] = date($format, strtotime($this->resource->get('editedon')) + $server_offset_time);
            $resourceArray['publishedon_by'] = $this->resource->get('publisher');
        } else {
            $resourceArray['publishedon_adjusted'] = $this->modx->lexicon('none');
            $resourceArray['publishedon_by'] = $this->modx->lexicon('none');
        }
        return $resourceArray;
    }
}
return 'modResourceDataProcessor';

/* get resource */
