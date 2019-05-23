<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Template;


use MODX\Revolution\modTemplate;
use MODX\Revolution\modTemplateVarTemplate;

/**
 * Create a template
 *
 * @property string  $templatename The name of the template
 * @property string  $content      The code of the template.
 * @property string  $description  (optional) A brief description.
 * @property integer $category     (optional) The category to assign to. Defaults to no
 * category.
 * @property boolean $locked       (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @property string  $propdata     (optional) A JSON object of properties
 * @property string  $tvs          (optional) A JSON array of TVs associated to the template
 *
 * @package MODX\Revolution\Processors\Element\Template
 */
class Create extends \MODX\Revolution\Processors\Element\Create
{
    public $classKey = modTemplate::class;
    public $languageTopics = ['template', 'category', 'element'];
    public $permission = 'new_template';
    public $objectType = 'template';
    public $beforeSaveEvent = 'OnBeforeTempFormSave';
    public $afterSaveEvent = 'OnTempFormSave';

    public function beforeSave()
    {
        $isStatic = intval($this->getProperty('static', 0));

        if ($isStatic == 1) {
            $staticFile = $this->getProperty('static_file');

            if (empty($staticFile)) {
                $this->addFieldError('static_file', $this->modx->lexicon('static_file_ns'));
            }
        }

        return parent::beforeSave();
    }

    public function afterSave()
    {
        $this->saveTemplateVariables();

        return parent::afterSave();
    }

    /**
     * Save template variables associated to the Template
     *
     * @return void
     */
    public function saveTemplateVariables()
    {
        /* change template access to tvs */
        $tvs = $this->getProperty('tvs', null);
        if ($tvs != null) {
            $templateVariables = is_array($tvs) ? $tvs : $this->modx->fromJSON($tvs);
            if (is_array($templateVariables)) {
                foreach ($templateVariables as $id => $tv) {
                    if ($tv['access']) {
                        /** @var modTemplateVarTemplate $templateVarTemplate */
                        $templateVarTemplate = $this->modx->getObject(modTemplateVarTemplate::class, [
                            'tmplvarid' => $tv['id'],
                            'templateid' => $this->object->get('id'),
                        ]);
                        if (empty($templateVarTemplate)) {
                            $templateVarTemplate = $this->modx->newObject(modTemplateVarTemplate::class);
                        }
                        $templateVarTemplate->set('tmplvarid', $tv['id']);
                        $templateVarTemplate->set('templateid', $this->object->get('id'));
                        $templateVarTemplate->set('rank', $tv['rank']);
                        $templateVarTemplate->save();
                    } else {
                        $templateVarTemplate = $this->modx->getObject(modTemplateVarTemplate::class, [
                            'tmplvarid' => $tv['id'],
                            'templateid' => $this->object->get('id'),
                        ]);
                        if ($templateVarTemplate && $templateVarTemplate instanceof modTemplateVarTemplate) {
                            $templateVarTemplate->remove();
                        }
                    }
                }
            }
        }
    }
}
