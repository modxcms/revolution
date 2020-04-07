<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element;


use MODX\Revolution\modElement;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modPropertySet;
use MODX\Revolution\modResource;
use MODX\Revolution\modSnippet;
use MODX\Revolution\modTemplateVar;

/**
 * Class GetInsertProperties
 *
 * @package MODX\Revolution\Processors\Element
 */
class GetInsertProperties extends Processor
{
    /** @var modElement $element */
    public $element;

    public function checkPermissions()
    {
        return $this->modx->hasPermission('element_tree');
    }

    public function getLanguageTopics()
    {
        return ['element', 'propertyset'];
    }

    public function initialize()
    {
        $this->setDefaultProperties([
            'classKey' => modSnippet::class,
            'pk' => false,
        ]);

        $this->element = $this->modx->getObject($this->getProperty('classKey'), $this->getProperty('pk'));
        if (empty($this->element)) {
            return $this->modx->lexicon('element_err_nf');
        }

        return true;
    }

    public function process()
    {
        $properties = $this->getElementProperties();
        $list = [];
        if (!empty($properties) && is_array($properties)) {
            foreach ($properties as $key => $property) {
                $propertyArray = $this->prepareProperty($key, $property);
                if (!empty($propertyArray)) {
                    $list[] = $propertyArray;
                }
            }
        }

        return $this->toJSON($list);
    }

    /**
     * Get the properties for the element
     *
     * @return array
     */
    public function getElementProperties()
    {
        $properties = $this->element->get('properties');
        $propertySet = $this->getProperty('propertySet');

        if (!empty($propertySet)) {
            /** @var modPropertySet $set */
            $set = $this->modx->getObject(modPropertySet::class, $propertySet);
            if ($set) {
                $setProperties = $set->get('properties');
                if (is_array($setProperties) && !empty($setProperties)) {
                    $properties = array_merge($properties, $setProperties);
                }
            }
        }

        return $properties;
    }

    /**
     * Prepare the property array for property insertion
     *
     * @param string $key
     * @param array  $property
     *
     * @return array
     */
    public function prepareProperty($key, array $property)
    {
        $xtype = 'textfield';
        $desc = $property['desc_trans'];
        if (!empty($property['lexicon'])) {
            $this->modx->lexicon->load($property['lexicon']);
        }

        if (is_array($property)) {
            $v = $property['value'];
            $xtype = $property['type'];
        } else {
            $v = $property;
        }

        $propertyArray = [];
        $listener = [
            'fn' => 'function() { Ext.getCmp(\'modx-window-insert-element\').changeProp(\'' . $key . '\'); }',
        ];
        switch ($xtype) {
            case 'list':
            case 'combo':
                $data = [];
                foreach ($property['options'] as $option) {
                    if (substr(trim($option['value']), 0, 1) === '@') {
                        /** @var modTemplateVar $tv */
                        $tv = $this->modx->newObject(modTemplateVar::class);
                        $bindingoptions = explode('||', $tv->processBindings($option['value']));

                        foreach ($bindingoptions as $bindingoption) {
                            $bindingoption = explode('==', $bindingoption);
                            $data[] = [$bindingoption[1], $bindingoption[0]];
                        }
                    } else {
                        if (empty($property['text']) && !empty($property['name'])) {
                            $property['text'] = $property['name'];
                        }
                        $text = !empty($property['lexicon']) ? $this->modx->lexicon($option['text']) : $option['text'];
                        $data[] = [$option['value'], $text];
                    }
                }
                $propertyArray = [
                    'xtype' => 'combo',
                    'fieldLabel' => $key,
                    'description' => $desc,
                    'name' => $key,
                    'value' => $v,
                    'width' => 300,
                    'id' => 'modx-iprop-' . $key,
                    'listeners' => ['select' => $listener],
                    'hiddenName' => $key,
                    'displayField' => 'd',
                    'valueField' => 'v',
                    'mode' => 'local',
                    'editable' => false,
                    'forceSelection' => true,
                    'typeAhead' => false,
                    'triggerAction' => 'all',
                    'store' => $data,
                ];
                break;
            case 'boolean':
            case 'modx-combo-boolean':
            case 'combo-boolean':
                $propertyArray = [
                    'xtype' => 'modx-combo-boolean',
                    'fieldLabel' => $key,
                    'description' => $desc,
                    'name' => $key,
                    'value' => $v,
                    'width' => 300,
                    'id' => 'modx-iprop-' . $key,
                    'listeners' => ['select' => $listener],
                ];
                break;
            case 'date':
            case 'datefield':
                $propertyArray = [
                    'xtype' => 'xdatetime',
                    'fieldLabel' => $key,
                    'description' => $desc,
                    'name' => $key,
                    'value' => $v,
                    'width' => 300,
                    'id' => 'modx-iprop-' . $key,
                    'listeners' => ['change' => $listener],
                ];
                break;
            case 'numberfield':
                $propertyArray = [
                    'xtype' => 'numberfield',
                    'fieldLabel' => $key,
                    'description' => $desc,
                    'name' => $key,
                    'value' => $v,
                    'width' => 300,
                    'id' => 'modx-iprop-' . $key,
                    'listeners' => ['change' => $listener],
                ];
                break;
            case 'textarea':
                $propertyArray = [
                    'xtype' => 'textarea',
                    'fieldLabel' => $key,
                    'description' => $desc,
                    'name' => $key,
                    'value' => $v,
                    'width' => 300,
                    'grow' => true,
                    'id' => 'modx-iprop-' . $key,
                    'listeners' => ['change' => $listener],
                ];
                break;
            case 'file':
                $resid = $this->getProperty('resourceId');
                if (!empty($resid)) {
                    $resobj = $this->modx->getObject(modResource::class, ['id' => $this->getProperty('resourceId')]);
                    $ctx = $resobj->get('context_key');
                    $orgctx = $this->modx->context->get('key');
                    $this->modx->switchContext($ctx);
                    $sourceid = $this->modx->getOption('default_media_source', null, 1);
                }

                $listener = [
                    'fn' => 'function(data) { 
                                if (data.fullRelativeUrl) {
                                    // sets the correct path in the select field
                                    Ext.getCmp(\'tvbrowser' . $key . '\').setValue(data.fullRelativeUrl);
                                    Ext.getCmp(\'modx-iprop-' . $key . '\').value = data.fullRelativeUrl;
                                } else {
                                    // sets the correct path in the select field
                                    Ext.getCmp(\'tvbrowser' . $key . '\').setValue(Ext.getCmp(\'tvbrowser' . $key . '\').getValue());
                                    Ext.getCmp(\'modx-iprop-' . $key . '\').value = Ext.getCmp(\'tvbrowser' . $key . '\').getValue();
                                }
                                Ext.getCmp(\'modx-window-insert-element\').changeProp(\'' . $key . '\');
                            }',
                ];

                $propertyArray = [
                    'xtype' => 'modx-panel-tv-file',
                    'source' => !empty($sourceid) ? $sourceid : 1,
                    'wctx' => !empty($ctx) ? $ctx : 'web',
                    'tv' => $key,
                    'fieldLabel' => $key,
                    'description' => $desc,
                    'name' => $key,
                    'value' => $v,
                    'width' => 300,
                    'id' => 'modx-iprop-' . $key,
                    'listeners' => ['change' => $listener, 'select' => $listener],
                ];
                $this->modx->switchContext($orgctx);
                break;
            case 'color':
                $data = [];
                foreach ($property['options'] as $option) {
                    if (substr(trim($option['value']), 0, 1) === '@') {
                        /** @var modTemplateVar $tv */
                        $tv = $this->modx->newObject(modTemplateVar::class);
                        $bindingoptions = explode('||', $tv->processBindings($option['value']));

                        foreach ($bindingoptions as $bindingoption) {
                            $bindingoption = explode('==', $bindingoption);
                            $data[] = [$bindingoption[1]];
                        }
                    } else {
                        $data[] = [$option['value']];
                    }
                }

                $listener = [
                    'fn' => 'function(palette, color) {
                                Ext.getCmp(\'modx-iprop-' . $key . '\').value = \'#\' + color;
                                Ext.getCmp(\'modx-window-insert-element\').changeProp(\'' . $key . '\');
                            }',
                ];

                $propertyArray = [
                    'xtype' => 'colorpalette',
                    'fieldLabel' => $key,
                    'description' => $desc,
                    'name' => $key,
                    'value' => $v,
                    'width' => 300, // unfortunately controlled by css class .x-color-palette
                    'id' => 'modx-iprop-' . $key,
                    'listeners' => ['select' => $listener],
                ];
                if (!empty($data)) {
                    $propertyArray['colors'] = $data;
                }
                break;
            default:
                $propertyArray = [
                    'xtype' => 'textfield',
                    'fieldLabel' => $key,
                    'description' => $desc,
                    'name' => $key,
                    'value' => $v,
                    'width' => 300,
                    'id' => 'modx-iprop-' . $key,
                    'listeners' => ['change' => $listener],
                ];
                break;
        }

        return $propertyArray;
    }

}
