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

# Set help descriptions
$descKeys = [
    'required_desc',
    'checkbox_columns_desc',
    'checkbox_display_switch_desc'
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
                columnWidth: 0.34,
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
                columnWidth: 0.33,
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
    }
]
OPTSJS;

return "{'success': 1, 'optsItems': $optsJS}";
