<?php
require_once (dirname(dirname(__FILE__)).'/create.class.php');
/**
 * Create a template
 *
 * @param string $templatename The name of the template
 * @param string $content The code of the template.
 * @param string $description (optional) A brief description.
 * @param integer $category (optional) The category to assign to. Defaults to no
 * category.
 * @param boolean $locked (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @param json $propdata (optional) A json array of properties
 * @param json $tvs (optional) A json array of TVs associated to the template
 *
 * @package modx
 * @subpackage processors.element.template
 */
class modTemplateCreateProcessor extends modElementCreateProcessor {
    public $classKey = 'modTemplate';
    public $languageTopics = array('template','category','element');
    public $permission = 'new_template';
    public $objectType = 'template';
    public $beforeSaveEvent = 'OnBeforeTempFormSave';
    public $afterSaveEvent = 'OnTempFormSave';

    public function beforeSave() {
        $isStatic = intval($this->getProperty('static', 0));

        if ($isStatic == 1) {
            $staticFile = $this->getProperty('static_file');

            if (empty($staticFile)) {
                $this->addFieldError('static_file', $this->modx->lexicon('static_file_ns'));
            }
        }

        return parent::beforeSave();
    }

    public function afterSave() {
        $this->saveTemplateVariables();
        return parent::afterSave();
    }

    /**
     * Save template variables associated to the Template
     * @return void
     */
    public function saveTemplateVariables() {
        /* change template access to tvs */
        $tvs = $this->getProperty('tvs',null);
        if ($tvs != null) {
            $templateVariables = is_array($tvs) ? $tvs : $this->modx->fromJSON($tvs);
            if (is_array($templateVariables)) {
                foreach ($templateVariables as $id => $tv) {
                    if ($tv['access']) {
                        /** @var modTemplateVarTemplate $templateVarTemplate */
                        $templateVarTemplate = $this->modx->getObject('modTemplateVarTemplate',array(
                            'tmplvarid' => $tv['id'],
                            'templateid' => $this->object->get('id'),
                        ));
                        if (empty($templateVarTemplate)) {
                            $templateVarTemplate = $this->modx->newObject('modTemplateVarTemplate');
                        }
                        $templateVarTemplate->set('tmplvarid',$tv['id']);
                        $templateVarTemplate->set('templateid',$this->object->get('id'));
                        $templateVarTemplate->set('rank',$tv['rank']);
                        $templateVarTemplate->save();
                    } else {
                        $templateVarTemplate = $this->modx->getObject('modTemplateVarTemplate',array(
                            'tmplvarid' => $tv['id'],
                            'templateid' => $this->object->get('id'),
                        ));
                        if ($templateVarTemplate && $templateVarTemplate instanceof modTemplateVarTemplate) {
                            $templateVarTemplate->remove();
                        }
                    }
                }
            }
        }
    }
}
return 'modTemplateCreateProcessor';
