<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution;


/**
 * An abstract class for extending Input Renders for TVs.
 *
 * @package MODX\Revolution
 */
abstract class modTemplateVarInputRender extends modTemplateVarRender {
    public function render($value,array $params = []) {
        $this->setPlaceholder('tv',$this->tv);
        $this->setPlaceholder('id',$this->tv->get('id'));
        $this->setPlaceholder('ctx',isset($_REQUEST['ctx']) ? $_REQUEST['ctx'] : 'web');
        $this->setPlaceholder('params',$params);

        $output = parent::render($value,$params);

        $tpl = $this->getTemplate();
        return !empty($tpl) ? $this->modx->controller->fetchTemplate($tpl) : $output;
    }

    /**
     * Set a placeholder to be used in the template
     * @param string $k
     * @param mixed $v
     */
    public function setPlaceholder($k,$v) {
        $this->modx->controller->setPlaceholder($k,$v);
    }

    /**
     * Return the template path to load
     * @return string
     */
    public function getTemplate() {
        return '';
    }

    /**
     * Return the input options parsed for the TV
     * @return mixed
     */
    public function getInputOptions() {
        return $this->tv->parseInputOptions($this->tv->processBindings($this->tv->get('elements'),$this->modx->resource->get('id')));
    }
}
