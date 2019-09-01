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
 * Duplicate a Template.
 *
 *
 * @property integer $id   The ID of the template to duplicate.
 * @property string  $name (optional) The name of the new template. Defaults to Untitled
 *                      Template.
 *
 * @package MODX\Revolution\Processors\Element\Template
 */
class Duplicate extends \MODX\Revolution\Processors\Element\Duplicate
{
    public $classKey = modTemplate::class;
    public $languageTopics = ['template'];
    public $permission = 'new_template';
    public $objectType = 'template';
    public $nameField = 'templatename';

    public function afterSave()
    {
        $this->duplicateTemplateVariables();

        return parent::afterSave();
    }

    public function duplicateTemplateVariables()
    {
        $c = $this->modx->newQuery(modTemplateVarTemplate::class);
        $c->where([
            'templateid' => $this->object->get('id'),
        ]);
        $c->sortby($this->modx->escape('rank'), 'ASC');
        $templateVarTemplates = $this->modx->getCollection(modTemplateVarTemplate::class, $c);
        /** @var modTemplateVarTemplate $templateVarTemplate */
        foreach ($templateVarTemplates as $templateVarTemplate) {
            /** @var modTemplateVarTemplate $newTemplateVarTemplate */
            $newTemplateVarTemplate = $this->modx->newObject(modTemplateVarTemplate::class);
            $newTemplateVarTemplate->set('tmplvarid', $templateVarTemplate->get('tmplvarid'));
            $newTemplateVarTemplate->set('rank', $templateVarTemplate->get('rank'));
            $newTemplateVarTemplate->set('templateid', $this->newObject->get('id'));
            $newTemplateVarTemplate->save();
        }
    }
}
