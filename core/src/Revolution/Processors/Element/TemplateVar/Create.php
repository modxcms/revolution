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
use MODX\Revolution\modTemplateVarResourceGroup;
use MODX\Revolution\modTemplateVarTemplate;
use MODX\Revolution\Sources\modMediaSourceElement;

/**
 * Create a Template Variable.
 *
 * @property string  $name            The name of the TV
 * @property string  $caption         (optional) A short caption for the TV.
 * @property string  $description     (optional) A brief description.
 * @property integer $category        (optional) The category to assign to. Defaults to no
 * category.
 * @property boolean $locked          (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @property string  $els             (optional)
 * @property integer $rank            (optional) The rank of the TV
 * @property string  $display         (optional) The type of output render
 * @property string  $display_params  (optional) Any display rendering parameters
 * @property string  $default_text    (optional) The default value for the TV
 * @property string  $templates       (optional) Templates associated with the TV
 * @property string  $resource_groups (optional) Resource Groups associated with the
 * TV.
 * @property string  $propdata        (optional) A JSON array of properties
 *
 * @package MODX\Revolution\Processors\Element\TemplateVar
 */
class Create extends \MODX\Revolution\Processors\Element\Create
{
    public $classKey = modTemplateVar::class;
    public $languageTopics = ['tv', 'category', 'element'];
    public $permission = 'new_tv';
    public $objectType = 'tv';
    public $beforeSaveEvent = 'OnBeforeTVFormSave';
    public $afterSaveEvent = 'OnTVFormSave';

    /**
     * Fire pre-save logic
     * {@inheritDoc}
     *
     * @return boolean
     */
    public function beforeSave()
    {
        $template = $this->getProperty('template');
        if (empty($template)) {
            $this->setProperty('template', []);
        }

        $caption = $this->getProperty('caption', '');
        if (empty($caption)) {
            $this->setProperty('caption', $this->getProperty('name'));
        }

        $this->setInputProperties();
        $this->setOutputProperties();

        $els = $this->getProperty('els', null);
        if ($els !== null) {
            $this->object->set('elements', $els);
        }

        $rank = $this->getProperty('rank', 0);
        $this->object->set('rank', $rank);

        return parent::beforeSave();
    }

    /**
     * Set the Output Options for the TV
     *
     * @return array
     */
    public function setOutputProperties()
    {
        $properties = $this->getProperties();
        $outputProperties = [];
        foreach ($properties as $key => $value) {
            $res = strstr($key, 'prop_');
            if ($res !== false) {
                $outputProperties[str_replace('prop_', '', $key)] = $value;
            }
        }
        $this->object->set('output_properties', $outputProperties);

        return $outputProperties;
    }

    /**
     * Set the Input Options for the TV
     *
     * @return array
     */
    public function setInputProperties()
    {
        $properties = $this->getProperties();
        $inputProperties = [];
        foreach ($properties as $key => $value) {
            $res = strstr($key, 'inopt_');
            if ($res !== false) {
                $inputProperties[str_replace('inopt_', '', $key)] = $value;
            }
        }
        $this->object->set('input_properties', $inputProperties);

        return $inputProperties;
    }

    /**
     * Add post-saving options to TVs
     *
     * {@inheritDoc}
     * @return boolean
     */
    public function afterSave()
    {
        $this->setTemplateAccess();
        $this->setResourceGroupAccess();
        $this->setMediaSources();

        return parent::afterSave();
    }

    /**
     * Set the Template Access to the TV
     *
     * @return void
     */
    public function setTemplateAccess()
    {
        $templates = $this->getProperty('templates', null);
        if ($templates !== null) {
            $templates = is_array($templates) ? $templates : $this->modx->fromJSON($templates);
            foreach ($templates as $id => $template) {
                if ($template['access']) {
                    /** @var modTemplateVarTemplate $templateVarTemplate */
                    $templateVarTemplate = $this->modx->getObject(modTemplateVarTemplate::class, [
                        'tmplvarid' => $this->object->get('id'),
                        'templateid' => $template['id'],
                    ]);
                    if (empty($templateVarTemplate)) {
                        $templateVarTemplate = $this->modx->newObject(modTemplateVarTemplate::class);
                    }
                    $templateVarTemplate->set('tmplvarid', $this->object->get('id'));
                    $templateVarTemplate->set('templateid', $template['id']);
                    $templateVarTemplate->set('rank', !empty($template['rank']) ? $template['rank'] : 0);
                    $templateVarTemplate->save();
                } else {
                    $templateVarTemplate = $this->modx->getObject(modTemplateVarTemplate::class, [
                        'tmplvarid' => $this->object->get('id'),
                        'templateid' => $template['id'],
                    ]);
                    if (!empty($templateVarTemplate)) {
                        $templateVarTemplate->remove();
                    }
                }
            }
        }

    }

    /**
     * Set Resource Groups access to TV
     *
     * @return array|string
     */
    public function setResourceGroupAccess()
    {
        $resourceGroups = $this->getProperty('resource_groups', null);
        if ($this->modx->hasPermission('tv_access_permissions') && $resourceGroups !== null) {
            $resourceGroups = is_array($resourceGroups) ? $resourceGroups : $this->modx->fromJSON($resourceGroups);
            if (is_array($resourceGroups)) {
                foreach ($resourceGroups as $id => $group) {
                    if (!is_array($group)) {
                        continue;
                    }
                    /** @var modTemplateVarResourceGroup $templateVarResourceGroup */
                    $templateVarResourceGroup = $this->modx->getObject(modTemplateVarResourceGroup::class, [
                        'tmplvarid' => $this->object->get('id'),
                        'documentgroup' => $group['id'],
                    ]);
                    if ($group['access'] == true) {
                        if (!empty($templateVarResourceGroup)) {
                            continue;
                        }
                        $templateVarResourceGroup = $this->modx->newObject(modTemplateVarResourceGroup::class);
                        $templateVarResourceGroup->set('tmplvarid', $this->object->get('id'));
                        $templateVarResourceGroup->set('documentgroup', $group['id']);
                        $templateVarResourceGroup->save();
                    } else {
                        $templateVarResourceGroup->remove();
                    }
                }
            }
        }
    }

    /**
     * Set source-element maps
     *
     * @return void
     */
    public function setMediaSources()
    {
        $sources = $this->getProperty('sources', null);
        if ($sources !== null) {
            $sources = is_array($sources) ? $sources : $this->modx->fromJSON($sources);
            if (is_array($sources)) {
                foreach ($sources as $id => $source) {
                    if (!is_array($source)) {
                        continue;
                    }

                    /** @var modMediaSourceElement $sourceElement */
                    $sourceElement = $this->modx->getObject(modMediaSourceElement::class, [
                        'object' => $this->object->get('id'),
                        'object_class' => $this->object->_class,
                        'context_key' => $source['context_key'],
                    ]);
                    if (!$sourceElement) {
                        $sourceElement = $this->modx->newObject(modMediaSourceElement::class);
                        $sourceElement->fromArray([
                            'object' => $this->object->get('id'),
                            'object_class' => $this->object->_class,
                            'context_key' => $source['context_key'],
                        ], '', true, true);
                    }
                    $sourceElement->set('source', $source['source']);
                    $sourceElement->save();
                }
            }
        }
    }
}
