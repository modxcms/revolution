<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Rte;

use MODX\Revolution\Processors\Processor;

/**
 * Get a list of registered RTEs
 * @package MODX\Revolution\Processors\System\Rte
 */
class GetList extends Processor
{
    public function process()
    {
        /** @var array|string $editors */
        $editors = $this->modx->invokeEvent('OnRichTextEditorRegister');
        if (empty($editors)) {
            $editors = [];
        }

        $list = [];
        $list[] = ['value' => $this->modx->lexicon('none')];
        if (is_array($editors)) {
            foreach ($editors as $editor) {
                $list[] = ['value' => $editor];
            }
        } elseif (is_string($editors)) {
            $list[] = ['value' => $editors];
        }
        return $this->outputArray($list);
    }
}
