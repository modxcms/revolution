<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\File\modFileHandler;
use MODX\Revolution\modTemplateVar;
use MODX\Revolution\modTemplateVarInputRender;
use MODX\Revolution\Sources\modMediaSource;

/**
 * @var modX $this->modx
 * @var modTemplateVar $this
 * @var array $params
 *
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderImage extends modTemplateVarInputRender {
    public function process($value, array $params = [])
    {
        $this->modx->getService('fileHandler', modFileHandler::class, '', ['context' => $this->modx->context->get('key')]);

        /** @var modMediaSource $source */
        $source = $this->tv->getSource($this->modx->resource->get('context_key'));
        if (!$source) return '';
        if (!$source->getWorkingContext()) {
            return '';
        }
        $source->setRequestProperties($_REQUEST);
        $source->initialize();
        $this->modx->controller->setPlaceholder('source', $source->get('id'));
        $params = array_merge($source->getPropertyList(), $params);

        $value = $this->tv->get('value');
        $this->tv->set('relativeValue', $value);
        if (!$source->checkPolicy('view')) {
            $this->setPlaceholder('disabled', true);
            $this->tv->set('disabled', true);
        } else {
            $this->setPlaceholder('disabled', false);
            $this->tv->set('disabled', false);
            if (!empty($value)) {
                $params['openTo'] = $source->getOpenTo($value, $params);
            }
        }
        if (!empty($value) && file_exists($source->getBasePath() . $value)) {
            $filename = $source->getBasePath() . $value;
            $hash = hash('crc32', filemtime($filename) . filesize($filename));
        } else {
            $hash = hash('crc32', $value);
        }

        $this->setPlaceholder('hash', $hash);
        $this->setPlaceholder('params', $params);
        $this->setPlaceholder('tv', $this->tv);
    }
    public function getTemplate() {
        return 'element/tv/renders/input/image.tpl';
    }
}
return 'modTemplateVarInputRenderImage';
