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
 * @var modX $this->modx
 * @var modTemplateVar $this
 * @var array $params
 *
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderImage extends modTemplateVarInputRender {
    public function process($value,array $params = array()) {
        $this->modx->getService('fileHandler','modFileHandler', '', array('context' => $this->modx->context->get('key')));

        /** @var modMediaSource $source */
        $source = $this->tv->getSource($this->modx->resource->get('context_key'));
        if (!$source) return '';
        if (!$source->getWorkingContext()) {
            return '';
        }
        $source->setRequestProperties($_REQUEST);
        $source->initialize();
        $this->modx->controller->setPlaceholder('source',$source->get('id'));
        $params = array_merge($source->getPropertyList(),$params);

        if (!$source->checkPolicy('view')) {
            $this->setPlaceholder('disabled',true);
            $this->tv->set('disabled',true);
            $this->tv->set('relativeValue',$this->tv->get('value'));
        } else {
            $this->setPlaceholder('disabled',false);
            $this->tv->set('disabled',false);
            $value = $this->tv->get('value');
            if (!empty($value)) {
                $params['openTo'] = $source->getOpenTo($value,$params);
            }
            $this->tv->set('relativeValue',$value);
        }

        $this->setPlaceholder('params',$params);
        $this->setPlaceholder('tv',$this->tv);
    }
    public function getTemplate() {
        return 'element/tv/renders/input/image.tpl';
    }
}
return 'modTemplateVarInputRenderImage';
