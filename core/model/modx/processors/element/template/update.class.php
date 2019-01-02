<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once (dirname(__DIR__).'/update.class.php');
/**
 * Update a template
 *
 * @param integer $id The ID of the template
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
class modTemplateUpdateProcessor extends modElementUpdateProcessor {
    public $classKey = 'modTemplate';
    public $languageTopics = array('template','category','element');
    public $permission = 'save_template';
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
        $this->setTemplateVariables();
        return parent::afterSave();
    }

    /**
     * Set the TemplateVar associations to this Template
     * @return void
     */
    public function setTemplateVariables() {
        $tvs = $this->getProperty('tvs',null);
        if ($tvs !== null) {
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
                        $templateVarTemplate->set('rank',isset($tv['tv_rank']) ? $tv['tv_rank'] : 0);
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
return 'modTemplateUpdateProcessor';
