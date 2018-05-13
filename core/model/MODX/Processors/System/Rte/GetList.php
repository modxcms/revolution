<?php

namespace MODX\Processors\System\Rte;

use MODX\Processors\modProcessor;

/**
 * Get a list of registered RTEs
 *
 * @package modx
 * @subpackage processors.system.rte
 */
class GetList extends modProcessor
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