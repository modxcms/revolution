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
    public $elementType = 'tv';

    public function beforeOutput() {
        $this->element->set('els',$this->element->get('elements'));
    }
}
return 'modTemplateVarGetProcessor';