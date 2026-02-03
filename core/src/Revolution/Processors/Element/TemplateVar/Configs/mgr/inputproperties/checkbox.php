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
 * @package MODX\Revolution\Processors\Element\TemplateVar\Configs\mgr\inputproperties
 */

# Set values
$columns = !empty($params['columns']) ? $params['columns'] : 1 ;
$displayAsSwitch = $params['displayAsSwitch'] === 'true' || $params['displayAsSwitch'] == 1 ? 'true' : 'false' ;
$columnDirection = !empty($params['columnDirection']) ? $params['columnDirection'] : 'vertical' ;
$columnWidth = !empty($params['columnWidth']) ? $params['columnWidth'] : 'medium' ;
$wrapColumnText = $params['wrapColumnText'] === 'true' || $params['wrapColumnText'] == 1 ? 'true' : 'false' ;

# Set help descriptions
$descKeys = [
    'required_desc',
    'checkbox_columns_desc',
    'checkbox_display_switch_desc',
    'checkbox_column_direction_desc',
    'checkbox_wrap_column_text_desc',
    'checkbox_column_width_desc'
];
$this->setHelpContent($descKeys, $expandHelp);

$optsJS = <<<OPTSJS
[
    {
        defaults: {
            xtype: 'panel',
            layout: 'form',
            labelAlign: 'top',
            autoHeight: true,
            labelSeparator: ''
        },
        items: [
            {
                columnWidth: 0.33,
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'combo-boolean',
                    fieldLabel: _('required'),
                    description: {$this->helpContent['eh_required_desc']},
                    name: 'inopt_allowBlank',
                    hiddenName: 'inopt_allowBlank',
                    id: 'inopt_allowBlank{$tvId}',
                    value: {$allowBlank}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_allowBlank{$tvId}',
                    html: {$this->helpContent['required_desc']},
                    cls: 'desc-under'
                }]
            },
            {
                columnWidth: 0.33,
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'combo-boolean',
                    fieldLabel: _('checkbox_display_switch'),
                    description: {$this->helpContent['eh_checkbox_display_switch_desc']},
                    name: 'inopt_displayAsSwitch',
                    hiddenName: 'inopt_displayAsSwitch',
                    id: 'inopt_displayAsSwitch{$tvId}',
                    value: {$displayAsSwitch}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_displayAsSwitch{$tvId}',
                    html: {$this->helpContent['checkbox_display_switch_desc']},
                    cls: 'desc-under'
                }]
            },
            {
                columnWidth: 0.34,
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'numberfield',
                    fieldLabel: _('checkbox_columns'),
                    description: {$this->helpContent['eh_checkbox_columns_desc']},
                    name: 'inopt_columns',
                    id: 'inopt_columns{$tvId}',
                    allowNegative: false,
                    allowDecimals: false,
                    value: {$columns}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_columns{$tvId}',
                    html: {$this->helpContent['checkbox_columns_desc']},
                    cls: 'desc-under'
                }]
            }
        ]
    },
    {
        defaults: {
            xtype: 'panel',
            layout: 'form',
            labelAlign: 'top',
            autoHeight: true,
            labelSeparator: ''
        },
        items: [
            {
                columnWidth: .33,
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'combo',
                    fieldLabel: _('checkbox_column_direction'),
                    description: {$this->helpContent['eh_checkbox_column_direction_desc']},
                    name: 'inopt_columnDirection',
                    hiddenName: 'inopt_columnDirection',
                    id: 'inopt_columnDirection{$tvId}',
                    mode: 'local',
                    store: new Ext.data.ArrayStore({
                        fields: [
                            'value',
                            'label'
                        ],
                        data: [
                            ['vertical', 'Vertical'],
                            ['horizontal', 'Horizontal']
                        ]
                    }),
                    valueField: 'value',
                    displayField: 'label',
                    triggerAction: 'all',
                    editable: false,
                    value: '{$columnDirection}'
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_columnDirection{$tvId}',
                    html: {$this->helpContent['checkbox_column_direction_desc']},
                    cls: 'desc-under'
                }]
            },
            {
                columnWidth: 0.33,
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'combo',
                    fieldLabel: _('checkbox_column_width'),
                    description: {$this->helpContent['eh_checkbox_column_width_desc']},
                    name: 'inopt_columnWidth',
                    hiddenName: 'inopt_columnWidth',
                    id: 'inopt_columnWidth{$tvId}',
                    mode: 'local',
                    store: new Ext.data.ArrayStore({
                        fields: [
                            'value',
                            'label'
                        ],
                        data: [
                            ['narrow', 'Narrow'],
                            ['medium', 'Medium'],
                            ['wide', 'Wide']
                        ]
                    }),
                    valueField: 'value',
                    displayField: 'label',
                    triggerAction: 'all',
                    editable: false,
                    value: '{$columnWidth}'
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_columnWidth{$tvId}',
                    html: {$this->helpContent['checkbox_column_width_desc']},
                    cls: 'desc-under'
                }]
            },
            {
                columnWidth: 0.34,
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'combo-boolean',
                    fieldLabel: _('checkbox_wrap_column_text'),
                    description: {$this->helpContent['eh_checkbox_wrap_column_text_desc']},
                    name: 'inopt_wrapColumnText',
                    hiddenName: 'inopt_wrapColumnText',
                    id: 'inopt_wrapColumnText{$tvId}',
                    value: {$wrapColumnText}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_wrapColumnText{$tvId}',
                    html: {$this->helpContent['checkbox_wrap_column_text_desc']},
                    cls: 'desc-under'
                }]
            }
        ]
    }
]
OPTSJS;

return "{'success': 1, 'optsItems': $optsJS}";
