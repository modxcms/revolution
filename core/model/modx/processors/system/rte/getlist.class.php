<?php
/**
 * Get a list of registered RTEs
 *
 * @package modx
 * @subpackage processors.system.rte
 */
class modSystemRteGetListProcessor extends modProcessor {
    public function process() {
        /** @var array|string $editors */
        $editors = $this->modx->invokeEvent('OnRichTextEditorRegister');
        if (empty($editors)) $editors == array();

        $list = array();
        $list[] = array('value' => $this->modx->lexicon('none'));
        if (is_array($editors)) {
            foreach ($editors as $editor) {
               $list[] = array('value' => $editor);
            }
        } elseif (is_string($editors)) {
            $list[] = array('value' => $editors);
        }
        return $this->outputArray($list);
    }
}
return 'modSystemRteGetListProcessor';