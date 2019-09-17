<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\TemplateVar;


use MODX\Revolution\modTemplateVar;
use MODX\Revolution\modTemplateVarResource;
use MODX\Revolution\modTemplateVarResourceGroup;
use MODX\Revolution\modTemplateVarTemplate;
use MODX\Revolution\Sources\modMediaSourceElement;

/**
 * Duplicate a TV
 *
 * @property integer $id   The ID of the TV to duplicate
 * @property string  $name The name of the new, duplicated TV
 *
 * @package MODX\Revolution\Processors\Element\TemplateVar
 */
class Duplicate extends \MODX\Revolution\Processors\Element\Duplicate
{
    public $classKey = modTemplateVar::class;
    public $languageTopics = ['tv'];
    public $permission = 'new_tv';
    public $objectType = 'tv';
    public $captionField = 'caption';

    public function afterSave()
    {
        $this->duplicateTemplates();
        $this->duplicateResources();
        $this->duplicateResourceGroups();
        $this->duplicateMediaSources();
    }

    /**
     * Duplicate Template associations
     *
     * @return void
     */
    public function duplicateTemplates()
    {
        $templateVarTemplates = $this->object->getMany('TemplateVarTemplates');
        if (is_array($templateVarTemplates) && !empty($templateVarTemplates)) {
            /** @var modTemplateVarTemplate $templateVarTemplate */
            foreach ($templateVarTemplates as $templateVarTemplate) {
                /** @var modTemplateVarTemplate $newTemplateVarTemplate */
                $newTemplateVarTemplate = $this->modx->newObject(modTemplateVarTemplate::class);
                $newTemplateVarTemplate->set('tmplvarid', $this->newObject->get('id'));
                $newTemplateVarTemplate->set('templateid', $templateVarTemplate->get('templateid'));
                $newTemplateVarTemplate->set('rank', $templateVarTemplate->get('rank'));
                $newTemplateVarTemplate->save();
            }
        }
    }

    /**
     * Duplicate the values of the TV across Resources using it
     *
     * @return void
     */
    public function duplicateResources()
    {
        if ($this->getProperty('duplicateValues', true)) {
            $templateVarResources = $this->object->getMany('TemplateVarResources');
            if (is_array($templateVarResources) && !empty($templateVarResources)) {
                /** @var modTemplateVarResource $templateVarResource */
                foreach ($templateVarResources as $templateVarResource) {
                    /** @var modTemplateVarResource $newTemplateVarResource */
                    $newTemplateVarResource = $this->modx->newObject(modTemplateVarResource::class);
                    $newTemplateVarResource->set('tmplvarid', $this->newObject->get('id'));
                    $newTemplateVarResource->set('contentid', $templateVarResource->get('contentid'));
                    $newTemplateVarResource->set('value', $templateVarResource->get('value'));
                    $newTemplateVarResource->save();
                }
            }
        }
    }

    /**
     * Duplicate Resource Group associations
     *
     * @return void
     */
    public function duplicateResourceGroups()
    {
        $resourceGroups = $this->object->getMany('TemplateVarResourceGroups');
        if (is_array($resourceGroups) && !empty($resourceGroups)) {
            /** @var modTemplateVarResourceGroup $resourceGroup */
            foreach ($resourceGroups as $resourceGroup) {
                /** @var modTemplateVarResourceGroup $newResourceGroup */
                $newResourceGroup = $this->modx->newObject(modTemplateVarResourceGroup::class);
                $newResourceGroup->set('tmplvarid', $this->newObject->get('id'));
                $newResourceGroup->set('documentgroup', $resourceGroup->get('documentgroup'));
                $newResourceGroup->save();
            }
        }
    }

    /**
     * Duplicate all media source associations
     *
     * @return void
     */
    public function duplicateMediaSources()
    {
        $sourceElements = $this->modx->getCollection(modMediaSourceElement::class, [
            'object' => $this->object->get('id'),
            'object_class' => modTemplateVar::class,
        ]);
        if (is_array($sourceElements) && !empty($sourceElements)) {
            /** @var modMediaSourceElement $sourceElement */
            foreach ($sourceElements as $sourceElement) {
                /** @var modMediaSourceElement $newSourceElement */
                $newSourceElement = $this->modx->newObject(modMediaSourceElement::class);
                $newSourceElement->fromArray([
                    'object' => $this->newObject->get('id'),
                    'object_class' => modTemplateVar::class,
                    'context_key' => $sourceElement->get('context_key'),
                    'source' => $sourceElement->get('source'),
                ], '', true, true);
                $newSourceElement->save();
            }
        }
    }

    /**
     * Get the new caption for the duplicate
     *
     * @return string
     */
    public function getNewCaption()
    {
        return $this->getProperty($this->captionField);
    }

    /**
     * Set the new caption to the new object
     *
     * @param $caption
     *
     * @return string
     * @internal param string $name
     */
    public function setNewCaption($caption)
    {
        return $this->newObject->set($this->captionField, $caption);
    }

    /**
     * Run any logic before the object has been duplicated. May return false to prevent duplication.
     *
     * @return boolean
     */
    public function beforeSave()
    {
        $caption = $this->getNewCaption();
        $this->setNewCaption($caption);

        return parent::beforeSave();
    }
}
