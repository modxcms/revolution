<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modResource;
use MODX\Revolution\modTemplateVar;
use MODX\Revolution\modTemplateVarInputRender;

/**
 * @var modX $this->modx
 * @var modTemplateVar $this
 *
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderResourceList extends modTemplateVarInputRender {
    public function process($value,array $params = []) {
        $parents = $this->getInputOptions();
        $params['parents'] = isset($params['parents']) ? $params['parents'] : '';
        $parents = !empty($params['parents']) || $params['parents'] === '0' ? explode(',',$params['parents']) : $parents;
        $params['depth'] = !empty($params['depth']) ? $params['depth'] : 10;
        if (empty($parents) || (empty($parents[0]) && $parents[0] !== '0')) {
            $parents = [];
        }
        $parentList = [];
        foreach ($parents as $parent) {
            /** @var modResource $parent */
            $parent = $this->modx->getObject(modResource::class,$parent);
            if ($parent) $parentList[] = $parent;
        }

        /* get all children */
        $ids = [];
        if (!empty($parentList)) {
            foreach ($parentList as $parent) {
                if (!empty($params['includeParent'])) $ids[] = $parent->get('id');
                $children = $this->modx->getChildIds($parent->get('id'),$params['depth'], [
                    'context' => $parent->get('context_key'),
                ]);
                $ids = array_merge($ids,$children);
            }
            $ids = array_unique($ids);
        }

        $c = $this->modx->newQuery(modResource::class);
        $c->leftJoin(modResource::class,'Parent');
        if (!empty($ids)) {
            $c->where(['modResource.id:IN' => $ids]);
        } else if (!empty($parents) && $parents[0] == 0) {
            $c->where(['modResource.parent' => 0]);
        }
        if (!empty($params['where'])) {
            $params['where'] = $this->modx->fromJSON($params['where']);
            $c->where($params['where']);
        }
        if (!empty($params['limitRelatedContext']) && ($params['limitRelatedContext'] == 1 || $params['limitRelatedContext'] == 'true')) {
            $context_key = $this->modx->resource->get('context_key');
            $c->where(['modResource.context_key' => $context_key]);
        }
        $c->sortby('Parent.menuindex,modResource.menuindex','ASC');
        if (!empty($params['limit'])) {
            $c->limit($params['limit']);
        }
        $resources = $this->modx->getCollection(modResource::class, $c);

        /* iterate */
        $opts[] = ['value' => '','text' => '-','selected' => $this->tv->get('value') == ''];

        /** @var modResource $resource */
        foreach ($resources as $resource) {
            $selected = $resource->get('id') == $this->tv->get('value');
            $opts[] = [
                'value' => $resource->get('id'),
                'text' => $resource->get('pagetitle').' ('.$resource->get('id').')',
                'selected' => $selected,
            ];
        }
        $this->setPlaceholder('opts',$opts);
    }
    public function getTemplate() {
        return 'element/tv/renders/input/resourcelist.tpl';
    }
}
return 'modTemplateVarInputRenderResourceList';
