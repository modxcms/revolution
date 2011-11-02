<?php
require_once (dirname(dirname(__FILE__)).'/get.class.php');
/**
 * Gets a TV
 *
 * @param integer $id The ID of the TV
 *
 * @package modx
 * @subpackage processors.element.tv
 */
class modTemplateVarGetProcessor extends modElementGetProcessor {
    public $classKey = 'modTemplateVar';
    public $languageTopics = array('tv','category');
    public $permission = 'view_tv';
    public $objectType = 'tv';

    public function beforeOutput() {
        parent::beforeOutput();
        $this->object->set('els',$this->object->get('elements'));
    }
}
return 'modTemplateVarGetProcessor';