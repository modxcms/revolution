<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\TemplateVar\Template;


use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modTemplateVarTemplate;

/**
 * Assigns or unassigns a template to a TV. Passed in JSON data.
 *
 * @property integer $id     The ID of the template
 * @property integer $tv     The ID of the tv
 * @property integer $rank   The rank of the tv-template relationship
 * @property boolean $access If true, the TV has access to the template.
 *
 * @package MODX\Revolution\Processors\Element\TemplateVar\Template
 */
class UpdateFromGrid extends Processor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('save_tv');
    }

    public function getLanguageTopics()
    {
        return ['tv'];
    }

    public function initialize()
    {
        $data = $this->getProperty('data');
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        $data = $this->modx->fromJSON($data);
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        $this->setProperties($data);

        return true;
    }

    public function process()
    {
        $fields = $this->getProperties();

        if (isset($fields['rank']) && empty($fields['rank'])) {
            $fields['rank'] = 0;
        }

        if (!empty($fields['access'])) {
            $templateVarTemplate = $this->addAccess($fields);
        } else {
            $templateVarTemplate = $this->removeAccess($fields);
        }

        return is_object($templateVarTemplate) && $templateVarTemplate instanceof modTemplateVarTemplate
            ? $this->success('', $templateVarTemplate)
            : $this->failure($templateVarTemplate);
    }

    /**
     * For adding access or updating rank
     *
     * @param array $fields
     *
     * @return modTemplateVarTemplate|string
     */
    public function addAccess(array $fields)
    {
        $templateVarTemplate = $this->modx->getObject(modTemplateVarTemplate::class, [
            'templateid' => $fields['id'],
            'tmplvarid' => $fields['tv'],
        ]);
        /** @var modTemplateVarTemplate $templateVarTemplate */
        if (empty($templateVarTemplate)) {
            $templateVarTemplate = $this->modx->newObject(modTemplateVarTemplate::class);
        }
        $templateVarTemplate->set('templateid', $fields['id']);
        $templateVarTemplate->set('tmplvarid', $fields['tv']);

        if ($templateVarTemplate->save() == false) {
            return $this->failure($this->modx->lexicon('tvt_err_save'));
        }

        return $templateVarTemplate;
    }

    /**
     * For removing access
     *
     * @param array $fields
     *
     * @return modTemplateVarTemplate|string
     */
    public function removeAccess(array $fields)
    {
        $templateVarTemplate = $this->modx->getObject(modTemplateVarTemplate::class, [
            'templateid' => $fields['id'],
            'tmplvarid' => $fields['tv'],
        ]);
        if (empty($templateVarTemplate)) {
            return $this->modx->lexicon('tvt_err_nf');
        }

        if ($templateVarTemplate->remove() == false) {
            return $this->modx->lexicon('tvt_err_remove');
        }

        return $templateVarTemplate;
    }
}

