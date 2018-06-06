<?php

namespace MODX\Processors\Element\Tv;

use MODX\modTemplateVarResourceGroup;
use MODX\modTemplateVarTemplate;
use MODX\Sources\modMediaSourceElement;

/**
 * Updates a TV
 *
 * @param string $name The name of the TV
 * @param string $caption (optional) A short caption for the TV.
 * @param string $description (optional) A brief description.
 * @param integer $category (optional) The category to assign to. Defaults to no
 * category.
 * @param boolean $locked (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @param string $els (optional)
 * @param integer $rank (optional) The rank of the TV
 * @param string $display (optional) The type of output render
 * @param string $display_params (optional) Any display rendering parameters
 * @param string $default_text (optional) The default value for the TV
 * @param string $templates (optional) Templates associated with the TV
 * @param string $resource_groups (optional) Resource Groups associated with the
 * TV.
 * @param string $propdata (optional) A json array of properties
 *
 * @package modx
 * @subpackage processors.element.tv
 */
class Update extends \MODX\Processors\Element\Update
{
    public $classKey = 'modTemplateVar';
    public $languageTopics = ['tv', 'category', 'element'];
    public $permission = 'save_tv';
    public $objectType = 'tv';
    public $beforeSaveEvent = 'OnBeforeTVFormSave';
    public $afterSaveEvent = 'OnTVFormSave';


    public function beforeSave()
    {
        $this->setInputProperties();
        $this->setOutputProperties();

        $els = $this->getProperty('els', null);
        if ($els !== null) {
            $this->object->set('elements', $els);
        }

        return parent::beforeSave();
    }


    public function afterSave()
    {
        $this->setTemplateAccess();
        $this->setResourceGroups();
        $this->setMediaSources();

        return parent::afterSave();
    }


    /**
     * Set the input properties based on the passed data
     *
     * @return array
     */
    public function setInputProperties()
    {
        $fields = $this->getProperties();
        $inputProperties = [];
        $inputPropertyFound = false;
        foreach ($fields as $key => $value) {
            $res = strstr($key, 'inopt_');
            if ($res !== false) {
                $inputProperties[str_replace('inopt_', '', $key)] = $value;
                $inputPropertyFound = true;
            }
        }
        if ($inputPropertyFound) {
            $this->object->set('input_properties', $inputProperties);
        }

        return $inputProperties;
    }


    /**
     * Set the output properties based on the passed data
     *
     * @return array
     */
    public function setOutputProperties()
    {
        $fields = $this->getProperties();
        $outputProperties = [];
        $outputPropertyFound = false;
        foreach ($fields as $key => $value) {
            $res = strstr($key, 'prop_');
            if ($res !== false) {
                $outputProperties[str_replace('prop_', '', $key)] = $value;
                $outputPropertyFound = true;
            }
        }
        if ($outputPropertyFound) {
            $this->object->set('output_properties', $outputProperties);
        }

        return $outputProperties;
    }


    /**
     * Set the Templates, if passed, for the TV
     *
     * @return void
     */
    public function setTemplateAccess()
    {
        $templates = $this->getProperty('templates', null);
        /* change template access to tvs */
        if ($templates !== null) {
            $templateVariables = is_array($templates) ? $templates : json_decode($templates, true);
            if (is_array($templateVariables)) {
                foreach ($templateVariables as $id => $template) {
                    if (!is_array($template)) continue;

                    if ($template['access']) {
                        /** @var modTemplateVarTemplate $templateVarTemplate */
                        $templateVarTemplate = $this->modx->getObject('modTemplateVarTemplate', [
                            'tmplvarid' => $this->object->get('id'),
                            'templateid' => $template['id'],
                        ]);
                        if (empty($templateVarTemplate)) {
                            $templateVarTemplate = $this->modx->newObject('modTemplateVarTemplate');
                        }
                        $templateVarTemplate->set('tmplvarid', $this->object->get('id'));
                        $templateVarTemplate->set('templateid', $template['id']);
                        $templateVarTemplate->save();
                    } else {
                        $templateVarTemplate = $this->modx->getObject('modTemplateVarTemplate', [
                            'tmplvarid' => $this->object->get('id'),
                            'templateid' => $template['id'],
                        ]);
                        if ($templateVarTemplate && $templateVarTemplate instanceof modTemplateVarTemplate) {
                            $templateVarTemplate->remove();
                        }
                    }
                }
            }
        }
    }


    /**
     * Set the Resource Groups, if passed, for the TV
     *
     * @return void
     */
    public function setResourceGroups()
    {
        $resourceGroups = $this->getProperty('resource_groups', null);
        if ($this->modx->hasPermission('access_permissions') && $resourceGroups !== null) {
            $resourceGroups = is_array($resourceGroups) ? $resourceGroups : json_decode($resourceGroups, true);
            if (is_array($resourceGroups)) {
                foreach ($resourceGroups as $id => $group) {
                    if (!is_array($group)) continue;
                    /** @var modTemplateVarResourceGroup $templateVarResourceGroup */
                    $templateVarResourceGroup = $this->modx->getObject('modTemplateVarResourceGroup', [
                        'tmplvarid' => $this->object->get('id'),
                        'documentgroup' => $group['id'],
                    ]);

                    if ($group['access'] == true) {
                        if (!empty($templateVarResourceGroup)) continue;
                        $templateVarResourceGroup = $this->modx->newObject('modTemplateVarResourceGroup');
                        $templateVarResourceGroup->set('tmplvarid', $this->object->get('id'));
                        $templateVarResourceGroup->set('documentgroup', $group['id']);
                        $templateVarResourceGroup->save();
                    } elseif ($templateVarResourceGroup && $templateVarResourceGroup instanceof modTemplateVarResourceGroup) {
                        $templateVarResourceGroup->remove();
                    }
                }
            }
        }
    }


    /**
     * Set the Media Source attributions, if passed, for the TV
     *
     * @return void
     */
    public function setMediaSources()
    {
        $sources = $this->getProperty('sources', null);
        if ($sources !== null) {
            $sources = is_array($sources) ? $sources : json_decode($sources, true);
            if (is_array($sources)) {
                $sourceElements = $this->modx->getCollection('sources.modMediaSourceElement', [
                    'object' => $this->object->get('id'),
                    'object_class' => 'MODX\modTemplateVar',
                ]);
                /** @var modMediaSourceElement $sourceElement */
                foreach ($sourceElements as $sourceElement) {
                    $sourceElement->remove();
                }

                foreach ($sources as $id => $source) {
                    if (!is_array($source)) {
                        continue;
                    }

                    /** @var modMediaSourceElement $sourceElement */
                    $sourceElement = $this->modx->getObject('sources.modMediaSourceElement', [
                        'object' => $this->object->get('id'),
                        'object_class' => $this->object->_class,
                        'context_key' => $source['context_key'],
                    ]);
                    if (!$sourceElement) {
                        $sourceElement = $this->modx->newObject('sources.modMediaSourceElement');
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


    public function cleanup()
    {
        return $this->success('', array_merge($this->object->get(['id', 'name', 'description', 'locked', 'category', 'default_text']), ['previous_category' => $this->previousCategory]));
    }
}
