<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modTemplateVarInputRender;

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderRichText extends modTemplateVarInputRender {
    public function process($value,array $params = []) {
        $which_editor = $this->modx->getOption('which_editor',null,'');
        $this->setPlaceholder('which_editor',$which_editor);
    }
    public function getTemplate() {
        return 'element/tv/renders/input/richtext.tpl';
    }
}
return 'modTemplateVarInputRenderRichText';
