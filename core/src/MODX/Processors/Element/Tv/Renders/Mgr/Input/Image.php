<?php

namespace MODX\Processors\Element\Tv\Renders\Mgr\Input;

use MODX\modTemplateVarInputRender;
use MODX\Sources\modMediaSource;

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class Image extends modTemplateVarInputRender
{
    public function process($value, array $params = [])
    {
        $this->modx->getService('fileHandler', 'modFileHandler', '', ['context' => $this->modx->context->get('key')]);

        /** @var modMediaSource $source */
        $source = $this->tv->getSource($this->modx->resource->get('context_key'));
        if (!$source || !$source->getWorkingContext()) {
            return;
        }
        $source->setRequestProperties($_REQUEST);
        $source->initialize();
        $this->modx->controller->setPlaceholder('source', $source->get('id'));
        $params = array_merge($source->getPropertyList(), $params);

        if (!$source->checkPolicy('view')) {
            $this->setPlaceholder('disabled', true);
            $this->tv->set('disabled', true);
            $this->tv->set('relativeValue', $this->tv->get('value'));
        } else {
            $this->setPlaceholder('disabled', false);
            $this->tv->set('disabled', false);
            $value = $this->tv->get('value');
            if (!empty($value)) {
                $params['openTo'] = $source->getOpenTo($value, $params);
            }
            $this->tv->set('relativeValue', $value);
        }

        $this->setPlaceholder('params', $params);
        $this->setPlaceholder('tv', $this->tv);
    }


    public function getTemplate()
    {
        return 'element/tv/renders/input/image.tpl';
    }
}