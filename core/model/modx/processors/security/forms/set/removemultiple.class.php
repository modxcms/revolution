<?php
/**
 * Remove multiple FC sets
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
class modFormCustomizationSetRemoveMultipleProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('customize_forms');
    }
    public function getLanguageTopics() {
        return array('formcustomization');
    }

    public function process() {
        $sets = $this->getProperty('sets');
        if (empty($sets)) {
            return $this->failure($this->modx->lexicon('set_err_ns'));
        }
        $setIds = explode(',',$sets);

        foreach ($setIds as $setId) {
            /** @var modFormCustomizationSet $set */
            $set = $this->modx->getObject('modFormCustomizationSet',$setId);
            if ($set) {
                if ($set->remove() === false) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,$this->modx->lexicon('set_err_remove'));
                }
            }
        }
        return $this->success();
    }
}
return 'modFormCustomizationSetRemoveMultipleProcessor';