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
$defaultHeight = 140;
$inputHeight = !empty($params['inputHeight']) ? $params['inputHeight'] : $defaultHeight ;
$textareaGrow = $this->modx->paramValueIsTrue($params, 'textareaGrow', true);
$textareaResizable = $this->modx->paramValueIsTrue($params, 'textareaResizable', true);

# Set help descriptions
$descKeys = [
    'required_desc',
    'input_height_desc',
    'textarea_grow_desc',
    'textarea_resizable_desc'
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
                columnWidth: 1,
                autoHeight: true,
                labelAlign: 'top',
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
                    tabIndex: 1,
                    value: {$allowBlank}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_allowBlank{$tvId}',
                    html: {$this->helpContent['required_desc']},
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
                columnWidth: 0.34,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'numberfield',
                    fieldLabel: _('input_height'),
                    description: {$this->helpContent['eh_input_height_desc']},
                    name: 'inopt_inputHeight',
                    id: 'inopt_inputHeight{$tvId}',
                    tabIndex: 2,
                    allowDecimals: false,
                    allowNegative: false,
                    value: {$inputHeight},
                    emptyText: {$defaultHeight}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_inputHeight{$tvId}',
                    html: {$this->helpContent['input_height_desc']},
                    cls: 'desc-under'
                }]
            },{
                xtype: 'panel',
                columnWidth: 0.33,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'combo-boolean',
                    fieldLabel: _('textarea_grow'),
                    description: {$this->helpContent['eh_textarea_grow_desc']},
                    name: 'inopt_textareaGrow',
                    hiddenName: 'inopt_textareaGrow',
                    id: 'inopt_textareaGrow{$tvId}',
                    tabIndex: 3,
                    value: {$textareaGrow}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_textareaGrow{$tvId}',
                    html: {$this->helpContent['textarea_grow_desc']},
                    cls: 'desc-under'
                }]
            },{
                xtype: 'panel',
                columnWidth: 0.33,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'combo-boolean',
                    fieldLabel: _('textarea_resizable'),
                    description: {$this->helpContent['eh_textarea_resizable_desc']},
                    name: 'inopt_textareaResizable',
                    hiddenName: 'inopt_textareaResizable',
                    id: 'inopt_textareaResizable{$tvId}',
                    tabIndex: 4,
                    value: {$textareaResizable}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_textareaResizable{$tvId}',
                    html: {$this->helpContent['textarea_resizable_desc']},
                    cls: 'desc-under'
                }]
            }
        ]
    }
]
OPTSJS;

return "{'success': 1, 'optsItems': $optsJS}";
