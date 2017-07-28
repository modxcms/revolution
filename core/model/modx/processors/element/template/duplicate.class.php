<?php
require_once (dirname(__DIR__).'/duplicate.class.php');
/**
 * Duplicate a Template.
 *
 *
 * @param integer $id The ID of the template to duplicate.
 * @param string $name (optional) The name of the new template. Defaults to Untitled
 * Template.
 *
 * @package modx
 * @subpackage processors.element.template
 */
class modTemplateDuplicateProcessor extends modElementDuplicateProcessor {
    public $classKey = 'modTemplate';
    public $languageTopics = array('template');
    public $permission = 'new_template';
    public $objectType = 'template';
    public $nameField = 'templatename';

    public function afterSave() {
        $this->duplicateTemplateVariables();
        return parent::afterSave();
    }

    public function duplicateTemplateVariables() {
        $c = $this->modx->newQuery('modTemplateVarTemplate');
        $c->where(array(
            'templateid' => $this->object->get('id'),
        ));
        $c->sortby('rank','ASC');
        $templateVarTemplates = $this->modx->getCollection('modTemplateVarTemplate',$c);
        /** @var modTemplateVarTemplate $templateVarTemplate */
        foreach ($templateVarTemplates as $templateVarTemplate) {
            /** @var modTemplateVarTemplate $newTemplateVarTemplate */
            $newTemplateVarTemplate = $this->modx->newObject('modTemplateVarTemplate');
            $newTemplateVarTemplate->set('tmplvarid',$templateVarTemplate->get('tmplvarid'));
            $newTemplateVarTemplate->set('rank',$templateVarTemplate->get('rank'));
            $newTemplateVarTemplate->set('templateid',$this->newObject->get('id'));
            $newTemplateVarTemplate->save();
        }
    }
}
return 'modTemplateDuplicateProcessor';