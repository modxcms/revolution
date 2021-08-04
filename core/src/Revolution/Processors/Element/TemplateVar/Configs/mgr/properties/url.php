<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * @package MODX\Revolution\Processors\Element\TemplateVar\Configs\mgr\properties
 */

# Set values
$displayText = !empty($params['text']) ? json_encode($params['text']) : 'null' ;
$title = !empty($params['title']) ? json_encode($params['title']) : 'null' ;
$id = !empty($params['id']) ? json_encode($params['id']) : 'null' ;
$class = !empty($params['class']) ? json_encode($params['class']) : 'null' ;
$style = !empty($params['style']) ? json_encode($params['style']) : 'null' ;
$target = !empty($params['target']) ? json_encode($params['target']) : "'_self'" ;
$attributes = !empty($params['attributes']) ? json_encode($params['attributes']) : 'null' ;

# Set help descriptions
$descKeys = [
    'display_text_desc',
    'attr_title_desc',
    'attr_id_desc',
    'attr_class_desc',
    'attr_style_desc',
    'attr_target_desc',
    'attr_attr_desc'
];
$this->setHelpContent($descKeys, $expandHelp);

$optsJS = <<<OPTSJS
[
    {
        defaults: {
            layout: 'form',
            labelSeparator: ''
        },
        items: [
            {
                xtype: 'panel',
                columnWidth: 0.5,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('url_display_text'),
                    description: {$this->helpContent['eh_display_text_desc']},
                    name: 'prop_text',
                    id: 'prop_text{$tvId}',
                    value: {$displayText}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_text{$tvId}',
                    html: {$this->helpContent['display_text_desc']},
                    cls: 'desc-under'
                }]
            },{
                xtype: 'panel',
                columnWidth: 0.5,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('title'),
                    description: {$this->helpContent['eh_attr_title_desc']},
                    name: 'prop_title',
                    id: 'prop_title{$tvId}',
                    value: {$title}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_title{$tvId}',
                    html: {$this->helpContent['attr_title_desc']},
                    cls: 'desc-under'
                }]
            }
        ]
    },
    {
        defaults: {
            layout: 'form',
            labelSeparator: ''
        },
        items: [
            {
                xtype: 'panel',
                columnWidth: 0.25,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('id'),
                    description: {$this->helpContent['eh_attr_id_desc']},
                    name: 'prop_id',
                    id: 'prop_id{$tvId}',
                    maskRe: '[^\\\s]',
                    regex: '^[^0-9\\\s][^\\\s]*$',
                    regexText: _('tv_err_invalid_id_attr'),
                    value: {$id}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_id{$tvId}',
                    html: {$this->helpContent['attr_id_desc']},
                    cls: 'desc-under'
                }]
            },{
                xtype: 'panel',
                columnWidth: 0.25,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('classes'),
                    description: {$this->helpContent['eh_attr_class_desc']},
                    name: 'prop_class',
                    id: 'prop_class{$tvId}',
                    value: {$class}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_class{$tvId}',
                    html: {$this->helpContent['attr_class_desc']},
                    cls: 'desc-under'
                }]
            },
            {
                xtype: 'panel',
                columnWidth: 0.5,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('style'),
                    description: {$this->helpContent['eh_attr_style_desc']},
                    name: 'prop_style',
                    id: 'prop_style{$tvId}',
                    value: {$style},
                    plugins: new AddFieldUtilities.plugin.Class
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_style{$tvId}',
                    html: {$this->helpContent['attr_style_desc']},
                    cls: 'desc-under',
                    listeners: 'insertHelpExampleOnClick'
                }]
            }
        ]
    },
    {
        defaults: {
            layout: 'form',
            labelSeparator: ''
        },
        items: [
            {
                xtype: 'panel',
                columnWidth: 0.5,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'combo',
                    fieldLabel: _('target'),
                    description: {$this->helpContent['eh_attr_target_desc']},
                    name: 'prop_target',
                    hiddenName: 'prop_target',
                    id: 'prop_target{$tvId}',
                    store: new Ext.data.SimpleStore({
                        fields: ['v','d']
                        ,data: [
                            ['_blank',_('attr_target_blank')],
                            ['_self',_('attr_target_self')],
                            ['_parent',_('attr_target_parent')],
                            ['_top',_('attr_target_top')]
                        ]
                    }),
                    displayField: 'd',
                    valueField: 'v',
                    mode: 'local',
                    editable: true,
                    forceSelection: false,
                    typeAhead: false,
                    triggerAction: 'all',
                    value: {$target}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_target{$tvId}',
                    html: {$this->helpContent['attr_target_desc']},
                    cls: 'desc-under'
                }]
            },{
                xtype: 'panel',
                columnWidth: 0.5,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('attributes'),
                    description: {$this->helpContent['eh_attr_attr_desc']},
                    name: 'prop_attributes',
                    id: 'prop_attributes{$tvId}',
                    value: {$attributes},
                    plugins: new AddFieldUtilities.plugin.Class
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_attributes{$tvId}',
                    html: {$this->helpContent['attr_attr_desc']},
                    cls: 'desc-under',
                    listeners: 'insertHelpExampleOnClick'
                }]
            }
        ]
    }
]
OPTSJS;

return "{'success': 1, 'optsItems': $optsJS}";
