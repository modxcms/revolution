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
use MODX\Revolution\modTemplateVarResource;
use xPDO\Om\xPDOQuery;

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderAutoTag extends modTemplateVarInputRender {
    public function getTemplate() {
        return 'element/tv/renders/input/autotag.tpl';
    }
    public function process($value,array $params = []) {
        if (empty($params['parent_resources'])) $params['parent_resources'] = '';
        $value = explode(",",$value);

        $default = explode("||",$this->tv->get('default_text'));

        $options = $this->getInputOptions();

        /** @var xPDOQuery $c */
        $c = $this->modx->newQuery(modTemplateVarResource::class);
        $c->innerJoin(modTemplateVar::class,'TemplateVar');
        $c->innerJoin(modResource::class,'Resource');
        $c->where([
            'tmplvarid' => $this->tv->get('id'),
        ]);
        if (!empty($params['parent_resources'])) {
            $ids = [];
            $parents = explode(',',$params['parent_resources']);

            $currCtx = 'web';
            $this->modx->switchContext('web');
            foreach ($parents as $id) {
                /** @var modResource $r */
                $r = $this->modx->getObject(modResource::class,$id);
                if ($r && $currCtx != $r->get('context_key')) {
                    $this->modx->switchContext($r->get('context_key'));
                    $currCtx = $r->get('context_key');
                }
                if ($r) {
                    $pids = $this->modx->getChildIds($id,10, ['context' => $r->get('context_key')]);
                    $ids = array_merge($ids,$pids);
                }
                $ids[] = $id;
            }
            $this->modx->switchContext('mgr');
            $ids = array_unique($ids);
            $c->where([
                'Resource.id:IN' => $ids,
            ]);
        }
        $tags = $this->modx->getCollection(modTemplateVarResource::class,$c);
        $options = [];
        /** @var modTemplateVarResource $tag */
        foreach ($tags as $tag) {
            $vs = explode(',',$tag->get('value'));
            $options = array_merge($options,$vs);
        }
        $options = array_unique($options);
        sort($options);
        $opts = [];
        $defaults = [];
        $i = 0;
        foreach ($options as $tag) {
            $checked = false;

            if (in_array($tag,$value)) { $checked = true; }
            if (in_array($tag,$default)) {
                $defaults[] = 'tv'.$this->tv->get('id').'-'.$i;
            }

            $opts[] = [
                'value' => htmlspecialchars($tag,ENT_COMPAT,'UTF-8'),
                'checked' => $checked,
            ];
            $i++;
        }

        $this->setPlaceholder('cbdefaults',implode(',',$defaults));
        $this->setPlaceholder('opts',$opts);
    }
}
return 'modTemplateVarInputRenderAutoTag';
